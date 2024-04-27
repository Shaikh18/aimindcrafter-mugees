<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use App\Models\SubscriptionPlan;
use App\Models\Transcript;
use App\Models\Workbook;
use App\Models\User;
use App\Models\ApiKey;
use Carbon\Carbon;


class TranscribeController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $projects = Workbook::where('user_id', auth()->user()->id)->get();

        return view('user.transcribe.index', compact('projects'));
    }


    /**
	*
	* Process Davinci Code
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function process(Request $request) 
    {
        if ($request->ajax()) {

            
            if (config('settings.personal_openai_api') == 'allow') {
                if (is_null(auth()->user()->personal_openai_key)) {
                    $data['status'] = 'error';
                    $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                    return $data; 
                } else {
                    $open_ai = new OpenAi(auth()->user()->personal_openai_key);
                } 
    
            } elseif (!is_null(auth()->user()->plan_id)) {
                $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                if ($check_api->personal_openai_api) {
                    if (is_null(auth()->user()->personal_openai_key)) {
                        $data['status'] = 'error';
                        $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                        return $data; 
                    } else {
                        $open_ai = new OpenAi(auth()->user()->personal_openai_key);
                    }
                } else {
                    if (config('settings.openai_key_usage') !== 'main') {
                       $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                       array_push($api_keys, config('services.openai.key'));
                       $key = array_rand($api_keys, 1);
                       $open_ai = new OpenAi($api_keys[$key]);
                   } else {
                       $open_ai = new OpenAi(config('services.openai.key'));
                   }
               }
    
            } else {
                if (config('settings.openai_key_usage') !== 'main') {
                    $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.openai.key'));
                    $key = array_rand($api_keys, 1);
                    $open_ai = new OpenAi($api_keys[$key]);
                } else {
                    $open_ai = new OpenAi(config('services.openai.key'));
                }
            }
            
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            $plan_type = (Auth::user()->group == 'subscriber') ? 'paid' : 'free';

            # Count minutes based on vendor requirements
            $audio_length = ((float)request('audiolength') / 60);    
            $audio_length = number_format((float)$audio_length, 3, '.', '');

            # Check if user has access to the template
            if (auth()->user()->group == 'user') {
                if (config('settings.whisper_feature_user') != 'allow') {
                    $data['status'] = 'error';
                    $data['message'] = __('AI Speech to Text feature is not available for your account, subscribe to get access');
                    return $data;
                }
            } elseif (!is_null(auth()->user()->group)) {
                $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                if ($plan) {
                    if (!$plan->transcribe_feature) {
                        $data['status'] = 'error';
                        $data['message'] = __('AI Speech to Text feature is not available for your subscription plan');
                        return $data;
    
                    }
                }
            }
 
            # Check if user has minutes available to proceed
            if (auth()->user()->available_minutes != -1) {
                if ((auth()->user()->available_minutes + auth()->user()->available_minutes_prepaid) < $audio_length) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_speech) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_minutes + $member->available_minutes_prepaid) < $audio_length) {
                                $data['status'] = 'error';
                                $data['message'] = __('Not enough available minutes to process. Subscribe or Top up to get more');
                                return $data;
                            }
                        } else {
                            $data['status'] = 'error';
                            $data['message'] = __('Not enough available minutes to process. Subscribe or Top up to get more');
                            return $data;
                        }
                        
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = __('Not enough available minutes to process. Subscribe or Top up to get more');
                        return $data;
                    } 
                } else {
                    $this->updateBalance($audio_length);
                } 
            }

            $uploading = new UserService();
            $upload = $uploading->prompt();
            if($upload['dota']!=622220){return;} 

            if (request()->has('audiofile')) {
        
                $audio = request()->file('audiofile');
                $format = $audio->getClientOriginalExtension();
                $file_name = $audio->getClientOriginalName();
                $size = $audio->getSize();
                $file_size = $this->formatBytes($size);
                $name = Str::random(10) . '.' . $format;

                
                if ($size > (config('settings.whisper_max_audio_size') * 1048576)) {
                    $data['status'] = 'error';
                    $data['message'] = __('File is too large, maximum allowed audio file size is:') . config('settings.whisper_max_audio_size') . 'MB';
                    return $data;
                } 
                
                if (config('settings.whisper_default_storage') == 'local') {
                    $audio_url = $audio->store('transcribe','public');
                } elseif (config('settings.whisper_default_storage') == 'aws') {
                    Storage::disk('s3')->put($name, file_get_contents($audio));
                    $audio_url = Storage::disk('s3')->url($name);
                } elseif (config('settings.whisper_default_storage') == 'r2') {
                    Storage::disk('r2')->put($name, file_get_contents($audio));
                    $audio_url = Storage::disk('r2')->url($name);
                } elseif (config('settings.whisper_default_storage') == 'wasabi') {
                    Storage::disk('wasabi')->put($name, file_get_contents($audio));
                    $audio_url = Storage::disk('wasabi')->url($name);
                } elseif (config('settings.whisper_default_storage') == 'gcp') {
                    Storage::disk('gcs')->put($name, file_get_contents($audio));
                    Storage::disk('gcs')->setVisibility($name, 'public');
                    $audio_url = Storage::disk('gcs')->url($name);
                    $storage = 'gcp';
                } elseif (config('settings.whisper_default_storage') == 'storj') {
                    Storage::disk('storj')->put($name, file_get_contents($audio), 'public');
                    Storage::disk('storj')->setVisibility($name, 'public');
                    $audio_url = Storage::disk('storj')->temporaryUrl($name, now()->addHours(167));
                    $storage = 'storj';                        
                } elseif (config('settings.whisper_default_storage') == 'dropbox') {
                    Storage::disk('dropbox')->put($name, file_get_contents($audio));
                    $audio_url = Storage::disk('dropbox')->url($name);
                    $storage = 'dropbox';
                }
            }

             # Audio Format
             if ($format == 'mp3') {
                $audio_type = 'audio/mpeg';
            } elseif ($format == 'ogg') {
                $audio_type = 'audio/ogg';
            } elseif($format == 'wav') {
                $audio_type = 'audio/wav';
            } elseif($format == 'webm') {
                $audio_type = 'audio/webm';
            }
            
            if (config('settings.whisper_default_storage') == 'local') {
                $file = curl_file_create($audio_url);
            } else {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_URL, $audio_url);
                $content = curl_exec($curl);
                Storage::disk('public')->put('transcribe/' . $file_name, $content);
                $file = curl_file_create('transcribe/' . $file_name);
                curl_close($curl);
                
            }

            if (request('task') == 'transcribe') {
              
                $complete = $open_ai->transcribe([
                    'model' => 'whisper-1',
                    'file' => $file,
                    'prompt' => request('description'),
                    'language' => request('language')
                ]);
            
            } else {

                $complete = $open_ai->translate([
                    'model' => 'whisper-1',
                    'file' => $file,
                    'prompt' => request('description'),
                ]);
            } 

            $response = json_decode($complete , true);

            if (isset($response['text'])) {

                $text = $response['text'];

                # Delete temp file
                if (config('settings.whisper_default_storage') != 'local') {
                    if (Storage::disk('public')->exists('transcribe/' . $file_name)) {
                        Storage::disk('public')->delete('transcribe/' . $file_name);
                    }
                }

                $words = count(explode(' ', ($text)));
                    
                $transcript = new Transcript();
                $transcript->user_id = auth()->user()->id;
                $transcript->transcript = $text;
                $transcript->title = request('document');
                $transcript->workbook = request('project');
                $transcript->description = request('description');
                $transcript->task = request('task');
                $transcript->format = $format;
                $transcript->words = $words;
                $transcript->size = $file_size;
                $transcript->file_name = $file_name;
                $transcript->temp_name = $name;
                $transcript->length = request('audiolength');
                $transcript->plan_type = $plan_type;
                $transcript->url = $audio_url;
                $transcript->audio_type = $audio_type;
                $transcript->storage = config('settings.whisper_default_storage');
                $transcript->save();
    
                $data['text'] = $text;
                $data['status'] = 'success';
                $data['url'] = (config('settings.whisper_default_storage') == 'local') ? URL::asset($audio_url) : $audio_url;
                $data['id'] = $transcript->id;
                $data['old'] = auth()->user()->available_minutes + auth()->user()->available_minutes_prepaid;
                $data['current'] = auth()->user()->available_minutes + auth()->user()->available_minutes_prepaid - floor($audio_length);
                return $data; 

            } else {

                if (isset($response['error']['message'])) {
                    $message = $response['error']['message'];
                } else {
                    $message = __('There is an issue with your openai account settings');
                }

                $data['status'] = 'error';
                $data['message'] = $message;
                return $data;
            }
           
        }
	}


    /**
	*
	* Update user minutes balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalance($minutes) {

        $user = User::find(Auth::user()->id);

        if (auth()->user()->available_minutes != -1) {
            
            if (Auth::user()->available_minutes > $minutes) {

                $total_minutes = Auth::user()->available_minutes - $minutes;
                $user->available_minutes = ($total_minutes < 0) ? 0 : $total_minutes;
    
            } elseif (Auth::user()->available_minutes_prepaid > $minutes) {
    
                $total_minutes_prepaid = Auth::user()->available_minutes_prepaid - $minutes;
                $user->available_minutes_prepaid = ($total_minutes_prepaid < 0) ? 0 : $total_minutes_prepaid;
    
            } elseif ((Auth::user()->available_minutes + Auth::user()->available_minutes_prepaid) == $minutes) {
    
                $user->available_minutes = 0;
                $user->available_minutes_prepaid = 0;
    
            } else {
    
                if (!is_null(Auth::user()->member_of)) {
    
                    $member = User::where('id', Auth::user()->member_of)->first();
    
                    if ($member->available_minutes > $minutes) {
    
                        $total_minutes = $member->available_minutes - $minutes;
                        $member->available_minutes = ($total_minutes < 0) ? 0 : $total_minutes;
            
                    } elseif ($member->available_minutes_prepaid > $minutes) {
            
                        $total_minutes_prepaid = $member->available_minutes_prepaid - $minutes;
                        $member->available_minutes_prepaid = ($total_minutes_prepaid < 0) ? 0 : $total_minutes_prepaid;
            
                    } elseif (($member->available_minutes + $member->available_minutes_prepaid) == $minutes) {
            
                        $member->available_minutes = 0;
                        $member->available_minutes_prepaid = 0;
            
                    } else {
                        $remaining = $minutes - $member->available_minutes;
                        $member->available_minutes = 0;
        
                        $prepaid_left = $member->available_minutes_prepaid - $remaining;
                        $member->available_minutes_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    }
    
                    $member->update();
    
                } else {
                    $remaining = $minutes - Auth::user()->available_minutes;
                    $user->available_images = 0;
    
                    $prepaid_left = Auth::user()->available_minutes_prepaid - $remaining;
                    $user->available_minutes_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                }
            }    
        }
    
        $user->update();

    }


    /**
	*
	* Save changes
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function save(Request $request) 
    {
        if ($request->ajax()) {

            $verify = $this->api->verify_license();
            if($verify['status']!=true){return false;}

            $transcript = Transcript::where('id', request('id'))->first(); 

            if ($transcript->user_id == Auth::user()->id){

                $transcript->transcript = $request->text;
                $transcript->title = $request->title;
                $transcript->save();

                $data['status'] = 'success';
                return $data;  
    
            } else{

                $data['status'] = 'error';
                return $data;
            }  
        }
	}


    public function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 

        $bytes /= pow(1024, $pow);
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }

}
