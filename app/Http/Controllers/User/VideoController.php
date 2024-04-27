<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\VideoResult;
use App\Models\User;
use App\Models\ApiKey;
use App\Models\Setting;
use GuzzleHttp\Exception\Report;
use DataTables;

class VideoController extends Controller
{
    private $api;
    private $user;

    public function __construct()
    {
        $this->api = new LicenseController();
        $this->user = new UserService();
    }

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = VideoResult::where('user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>
                                        <a class="deleteResultButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Result') .'"></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd/m/Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('download', function($row){
                        $url = ($row['storage'] == 'local') ? URL::asset($row['video']) : $row['video'];
                        $result = '<a class="" href="' . $url . '" download><i class="fa fa-cloud-download table-action-buttons download-action-button" title="'. __('Download Result') .'"></i></a>';
                        return $result;
                    })
                    ->addColumn('custom-image', function($row){ 
                        $result = ($row['storage'] == 'local') ? URL::asset($row['image']) : $row['image'];
                        $image = '<span class="video-image-view overflow-hidden"><img src="' . $result . '"></span> ';            
                        return $image;
                    })
                    ->addColumn('custom-video', function($row){ 
                        if ($row['status'] == 'processing') {
                            return '';
                        } else {
                            $link = ($row['storage'] == 'local') ? URL::asset($row['video']) : $row['video'];
                            $result = '<video controls preload="metadata" style="width: 350px; height: 150px; border-radius: 10px;"><source src="'. $link .'"></video>';          
                            return $result;
                        }
                    })
                    ->addColumn('custom-status', function($row){ 
                        $status = '<span class="cell-box video-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $status;
                    })
                    ->rawColumns(['actions', 'created-on', 'custom-image', 'download', 'custom-status', 'custom-video'])
                    ->make(true);  
        }  
        
        $verify = $this->api->verify_license();
        $type = (isset($verify['type'])) ? $verify['type'] : '';

        $this->verify();

        if (auth()->user()->group == 'user') {
            if (config('settings.video_user_access') != 'allow') {
                toastr()->warning(__('AI Image to Video feature is not available for free tier users, subscribe to get a proper access'));
                return redirect()->route('user.plans');
            } else {
                return view('user.video.index', compact('type'));
            }
        } elseif (auth()->user()->group == 'subscriber') {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan->video_image_feature == false) {     
                toastr()->warning(__('Your current subscription plan does not include support for AI Image to Video feature'));
                return redirect()->back();                   
            } else {
                return view('user.video.index', compact('type'));
            }
        } else {
            return view('user.video.index', compact('type'));
        }

    }


    /**
	*
	* Process Davinci Image to Video
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function create(Request $request) 
    {
        if ($request->ajax()) {

            if (config('settings.personal_sd_api') == 'allow') {
                if (is_null(auth()->user()->personal_sd_key)) {
                    $data['status'] = 'error';
                    $data['message'] = __('You must include your personal Stable Diffusion API key in your profile settings first');
                    return $data; 
                } else {
                    $stable_diffusion = auth()->user()->personal_sd_key;
                } 
    
            } elseif (!is_null(auth()->user()->plan_id)) {
                $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                if ($check_api->personal_sd_api) {
                    if (is_null(auth()->user()->personal_sd_key)) {
                        $data['status'] = 'error';
                        $data['message'] = __('You must include your personal Stable Diffusion API key in your profile settings first');
                        return $data; 
                    } else {
                        $stable_diffusion = auth()->user()->personal_sd_key;
                    }
                } else {
                    if (config('settings.sd_key_usage') == 'main') {
                        $stable_diffusion = config('services.stable_diffusion.key');
                    } else {
                        $api_keys = ApiKey::where('engine', 'stable_diffusion')->where('status', true)->pluck('api_key')->toArray();
                        array_push($api_keys, config('services.stable_diffusion.key'));
                        $key = array_rand($api_keys, 1);
                        $stable_diffusion = $api_keys[$key];
                    }
                }        
            } else {
                if (config('settings.sd_key_usage') == 'main') {
                    $stable_diffusion = config('services.stable_diffusion.key');
                } else {
                    $api_keys = ApiKey::where('engine', 'stable_diffusion')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.stable_diffusion.key'));
                    $key = array_rand($api_keys, 1);
                    $stable_diffusion = $api_keys[$key];
                }
            }
            

            # Verify if user has enough credits
            if (auth()->user()->available_sd_images != -1) {
                if ((auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid) < config('settings.cost_per_image_to_video')) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_image) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_sd_images + $member->available_sd_images_prepaid) < config('settings.cost_per_image_to_video')) {
                                $data['status'] = 'error';
                                $data['message'] = __('Not enough Stable Diffusion image balance to proceed, subscribe or top up your image balance and try again');
                                return $data;
                            }
                        } else {
                            $data['status'] = 'error';
                            $data['message'] = __('Not enough Stable Diffusion image balance to proceed, subscribe or top up your image balance and try again');
                            return $data;
                        }
                        
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = __('Not enough Stable Diffusion image balance to proceed, subscribe or top up your image balance and try again');
                        return $data;
                    } 
                }
            }


            $plan_type = (auth()->user()->plan_id) ? 'paid' : 'free'; 
            $settings = Setting::where('name', 'license')->first(); 
            $verify = $this->user->verify_license();
            if($settings->value != $verify['code']){return;}

            $url = 'https://api.stability.ai/v2alpha/generation/image-to-video';
            $output = 'a1d1c037d177f38570f2c4772d4402ac';

            $image_path = request()->file('image')->getRealPath();
            $image_extension = request()->file('image')->getClientOriginalExtension();

            $name = 'image-' . Str::random(10) . '.' . $image_extension;

            if (config('settings.default_storage') == 'local') {
                Storage::disk('public')->put('images/' . $name, file_get_contents($image_path));
                $image_url = 'images/' . $name;
                $storage = 'local';
            } elseif (config('settings.default_storage') == 'aws') {
                Storage::disk('s3')->put('images/' . $name, file_get_contents($image_path), 'public');
                $image_url = Storage::disk('s3')->url('images/' . $name);
                $storage = 'aws';
            } elseif (config('settings.default_storage') == 'r2') {
                Storage::disk('r2')->put('images/' . $name, file_get_contents($image_path), 'public');
                $image_url = Storage::disk('r2')->url('images/' . $name);
                $storage = 'r2';
            } elseif (config('settings.default_storage') == 'wasabi') {
                Storage::disk('wasabi')->put('images/' . $name, file_get_contents($image_path));
                $image_url = Storage::disk('wasabi')->url('images/' . $name);
                $storage = 'wasabi';
            } elseif (config('settings.default_storage') == 'gcp') {
                Storage::disk('gcs')->put('images/' . $name, file_get_contents($image_path));
                Storage::disk('gcs')->setVisibility('images/' . $name, 'public');
                $image_url = Storage::disk('gcs')->url('images/' . $name);
                $storage = 'gcp';
            } elseif (config('settings.default_storage') == 'storj') {
                Storage::disk('storj')->put('images/' . $name, file_get_contents($image_path), 'public');
                Storage::disk('storj')->setVisibility('images/' . $name, 'public');
                $image_url = Storage::disk('storj')->temporaryUrl('images/' . $name, now()->addHours(167));
                $storage = 'storj';                        
            } elseif (config('settings.default_storage') == 'dropbox') {
                Storage::disk('dropbox')->put('images/' . $name, file_get_contents($image_path));
                $image_url = Storage::disk('dropbox')->url('images/' . $name);
                $storage = 'dropbox';
            }

            $init = new Report(); $file = $init->upload();
            $ch = curl_init();
                
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: multipart/form-data',
                'Accept: application/json',
                'Authorization: Bearer '.$stable_diffusion
            ));

           if(md5($file['type']) != $output) return;
            $postFields = array(
                'image' => new \CURLFile($image_path),
                'motion_bucket_id' => (int)$request->motion_bucket_id,
                'seed' => (int)$request->seed,
                'cfg_scale' => (int)$request->cfg_scale,
            ); 

            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_post_fields($postFields)); 
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result , true);

            if (isset($response['id'])) {
                
                # Update credit balance
                $this->updateBalance(config('settings.cost_per_image_to_video'));

                $video = new VideoResult([
                    'user_id' => Auth::user()->id,
                    'image' => $image_url,
                    'storage' => $storage,
                    'job_id' => $response['id'],
                    'status' => 'processing',
                    'seed' => $request->seed,
                    'cfg_scale' => $request->cfg_scale,
                    'motion_bucket_id' => $request->motion_bucket_id,
                ]);

                $video->save();

                $data['status'] = 'success';
                $data['message'] = __('AI Image to Video task has been successfully created');
                $data['old'] = auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid;
                $data['current'] = auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid - config('settings.cost_per_image_to_video');
                $data['balance'] = (auth()->user()->available_sd_images == -1) ? 'unlimited' : 'counted';
                return $data; 

            } else {

                if (isset($response['name'])) {
                    if ($response['name'] == 'insufficient_balance') {
                        $message = __('You do not have sufficent balance in your Stable Diffusion account to generate new videos');
                    } elseif ($response['name'] == 'bad_request') {
                        $message = $response['errors'][0];
                    } else {
                        $message =  __('There was an issue generating your AI Video, please try again or contact support team');
                    }
                } else {
                    $message = __('There was an issue generating your AI Video, please try again or contact support team');
                }

                $data['status'] = 'error';
                $data['message'] = $message;
                return $data;
            }
        }
	}


    public function verify() 
    {

        if (config('settings.personal_sd_api') == 'allow') {
            if (is_null(auth()->user()->personal_sd_key)) {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Stable Diffusion API key in your profile settings first');
                return $data; 
            } else {
                $stable_diffusion = auth()->user()->personal_sd_key;
            } 

        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_sd_api) {
                if (is_null(auth()->user()->personal_sd_key)) {
                    $data['status'] = 'error';
                    $data['message'] = __('You must include your personal Stable Diffusion API key in your profile settings first');
                    return $data; 
                } else {
                    $stable_diffusion = auth()->user()->personal_sd_key;
                }
            } else {
                if (config('settings.sd_key_usage') == 'main') {
                    $stable_diffusion = config('services.stable_diffusion.key');
                } else {
                    $api_keys = ApiKey::where('engine', 'stable_diffusion')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.stable_diffusion.key'));
                    $key = array_rand($api_keys, 1);
                    $stable_diffusion = $api_keys[$key];
                }
            }        
        } else {
            if (config('settings.sd_key_usage') == 'main') {
                $stable_diffusion = config('services.stable_diffusion.key');
            } else {
                $api_keys = ApiKey::where('engine', 'stable_diffusion')->where('status', true)->pluck('api_key')->toArray();
                array_push($api_keys, config('services.stable_diffusion.key'));
                $key = array_rand($api_keys, 1);
                $stable_diffusion = $api_keys[$key];
            }
        }

        $settings = Setting::where('name', 'license')->first(); 
        $verify = $this->user->verify_license();
        if($settings->value != $verify['code']){return;}

        $videos = VideoResult::where('user_id', auth()->user()->id)->where('status', 'processing')->get();

        if ($videos) {
            foreach ($videos as $video) {
                $id = $video->job_id;

                $url = 'https://api.stability.ai/v2alpha/generation/image-to-video/result/' . $id;

                $ch = curl_init();
            
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept: application/json',
                    'Authorization: Bearer '.$stable_diffusion
                ));

                $result = curl_exec($ch);
                curl_close($ch);

                $response = json_decode($result , true);

                if (isset($response['finishReason'])) {

                    if ($response['finishReason'] == 'SUCCESS') {

                        $name = 'video-' . Str::random(10) . '.mp4';

                        if (config('settings.default_storage') == 'local') {
                            Storage::disk('public')->put('images/' . $name, base64_decode($response['video']));
                            $video_url = 'images/' . $name;
                        } elseif (config('settings.default_storage') == 'aws') {
                            Storage::disk('s3')->put('images/' . $name, base64_decode($response['video']), 'public');
                            $video_url = Storage::disk('s3')->url('images/' . $name);
                        } elseif (config('settings.default_storage') == 'r2') {
                            Storage::disk('r2')->put('images/' . $name, base64_decode($response['video']), 'public');
                            $video_url = Storage::disk('r2')->url('images/' . $name);
                        } elseif (config('settings.default_storage') == 'wasabi') {
                            Storage::disk('wasabi')->put('images/' . $name, base64_decode($response['video']));
                            $video_url = Storage::disk('wasabi')->url('images/' . $name);
                        } elseif (config('settings.default_storage') == 'gcp') {
                            Storage::disk('gcs')->put('images/' . $name, base64_decode($response['video']));
                            Storage::disk('gcs')->setVisibility('images/' . $name, 'public');
                            $video_url = Storage::disk('gcs')->url('images/' . $name);
                        } elseif (config('settings.default_storage') == 'storj') {
                            Storage::disk('storj')->put('images/' . $name, base64_decode($response['video']), 'public');
                            Storage::disk('storj')->setVisibility('images/' . $name, 'public');
                            $video_url = Storage::disk('storj')->temporaryUrl('images/' . $name, now()->addHours(167));                       
                        } elseif (config('settings.default_storage') == 'dropbox') {
                            Storage::disk('dropbox')->put('images/' . $name, base64_decode($response['video']));
                            $video_url = Storage::disk('dropbox')->url('images/' . $name);
                        }

                        $video->update([
                            'video' => $video_url,
                            'status' => 'completed',
                        ]);
                    }

                }

            }
        }

	}


    /**
	*
	* Update user image balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalance($images) {

        $user = User::find(Auth::user()->id);

        if (auth()->user()->available_sd_images != -1) {
        
            if (Auth::user()->available_sd_images > $images) {

                $total_images = Auth::user()->available_sd_images - $images;
                $user->available_sd_images = ($total_images < 0) ? 0 : $total_images;

            } elseif (Auth::user()->available_sd_images_prepaid > $images) {

                $total_images_prepaid = Auth::user()->available_sd_images_prepaid - $images;
                $user->available_sd_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;

            } elseif ((Auth::user()->available_sd_images + Auth::user()->available_sd_images_prepaid) == $images) {

                $user->available_sd_images = 0;
                $user->available_sd_images_prepaid = 0;

            } else {

                if (!is_null(Auth::user()->member_of)) {

                    $member = User::where('id', Auth::user()->member_of)->first();

                    if ($member->available_sd_images > $images) {

                        $total_images = $member->available_sd_images - $images;
                        $member->available_sd_images = ($total_images < 0) ? 0 : $total_images;
            
                    } elseif ($member->available_sd_images_prepaid > $images) {
            
                        $total_images_prepaid = $member->available_sd_images_prepaid - $images;
                        $member->available_sd_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;
            
                    } elseif (($member->available_sd_images + $member->available_sd_images_prepaid) == $images) {
            
                        $member->available_sd_images = 0;
                        $member->available_sd_images_prepaid = 0;
            
                    } else {
                        $remaining = $images - $member->available_sd_images;
                        $member->available_sd_images = 0;
        
                        $prepaid_left = $member->available_sd_images_prepaid - $remaining;
                        $member->available_sd_images_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    }

                    $member->update();

                } else {
                    $remaining = $images - Auth::user()->available_sd_images;
                    $user->available_sd_images = 0;

                    $prepaid_left = Auth::user()->available_sd_images_prepaid - $remaining;
                    $user->available_sd_images_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                }
            }
        }

        $user->update();

    }


     /**
	*
	* Process media file
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function view(Request $request) 
    {
        if ($request->ajax()) {

            $image = VideoResult::where('id', request('id'))->first(); 

            if ($image) {
                if ($image->user_id == Auth::user()->id){


                    $data['status'] = 'success';
                    return $data;
        
                } else{
    
                    $data['status'] = 'error';
                    $data['message'] = __('There was an error while retrieving this image');
                    return $data;
                }  
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Image was not found');
                return $data;
            }
            
        }
	}


    /**
	*
	* Delete File
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function delete(Request $request) 
    {
        if ($request->ajax()) {

            $image = VideoResult::where('id', request('id'))->first(); 

            if ($image->user_id == auth()->user()->id){

                switch ($image->storage) {
                    case 'local':
                        if (Storage::disk('public')->exists($image->image)) {
                            Storage::disk('public')->delete($image->image);
                        }
                        break;
                    case 'aws':
                        if (Storage::disk('s3')->exists($image->image_name)) {
                            Storage::disk('s3')->delete($image->image_name);
                        }
                        break;
                    case 'r2':
                        if (Storage::disk('r2')->exists($image->image_name)) {
                            Storage::disk('r2')->delete($image->image_name);
                        }
                        break;
                    case 'wasabi':
                        if (Storage::disk('wasabi')->exists($image->image_name)) {
                            Storage::disk('wasabi')->delete($image->image_name);
                        }
                        break;
                    case 'storj':
                        if (Storage::disk('storj')->exists($image->image_name)) {
                            Storage::disk('storj')->delete($image->image_name);
                        }
                        break;
                    case 'gcp':
                        if (Storage::disk('gcs')->exists($image->image_name)) {
                            Storage::disk('gcs')->delete($image->image_name);
                        }
                        break;
                    case 'dropbox':
                        if (Storage::disk('dropbox')->exists($image->image_name)) {
                            Storage::disk('dropbox')->delete($image->image_name);
                        }
                        break;
                    default:
                        # code...
                        break;
                }

                $image->delete();

                $data['status'] = 'success';
                return $data;  
    
            } else{

                $data['status'] = 'error';
                $data['message'] = __('There was an error while deleting the image');
                return $data;
            }  
        }
	}


    public function build_post_fields( $data,$existingKeys='',&$returnArray=[])
    {
        if(($data instanceof \CURLFile) or !(is_array($data) or is_object($data))){
            $returnArray[$existingKeys]=$data;
            return $returnArray;
        }
        else{
            foreach ($data as $key => $item) {
                $this->build_post_fields($item,$existingKeys?$existingKeys."[$key]":$key,$returnArray);
            }
            return $returnArray;
        }
    }


    public function refresh(Request $request) 
    {
        if ($request->ajax()) {

            $this->verify();

            return 200;
        }
    }

}
