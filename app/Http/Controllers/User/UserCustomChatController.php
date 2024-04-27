<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use OpenAI\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ChatCategory;
use App\Models\CustomChat;
use Yajra\DataTables\DataTables;
use App\Models\SubscriptionPlan;
use App\Models\ApiKey;

class UserCustomChatController extends Controller
{

    private $client;

    public function __construct()
    {
        // if (config('settings.personal_openai_api') == 'allow') {
        //     if (is_null(auth()->user()->personal_openai_key)) {
        //         $data['status'] = 'error';
        //         $data['message'] = __('You must include your personal Openai API key in your profile settings first');
        //         return $data; 
        //     } else {
        //         $open_ai = new OpenAi(auth()->user()->personal_openai_key);
        //     } 

        // } elseif (!is_null(auth()->user()->plan_id)) {
        //     $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
        //     if ($check_api->personal_openai_api) {
        //         if (is_null(auth()->user()->personal_openai_key)) {
        //             $data['status'] = 'error';
        //             $data['message'] = __('You must include your personal Openai API key in your profile settings first');
        //             return $data; 
        //         } else {
        //             $open_ai = new OpenAi(auth()->user()->personal_openai_key);
        //         }
        //     } else {
        //         if (config('settings.openai_key_usage') !== 'main') {
        //            $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
        //            array_push($api_keys, config('services.openai.key'));
        //            $key = array_rand($api_keys, 1);
        //            $open_ai = new OpenAi($api_keys[$key]);
        //        } else {
        //            $open_ai = new OpenAi(config('services.openai.key'));
        //        }
        //    }

        // } else {
        //     if (config('settings.openai_key_usage') !== 'main') {
        //         $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
        //         array_push($api_keys, config('services.openai.key'));
        //         $key = array_rand($api_keys, 1);
        //         $open_ai = new OpenAi($api_keys[$key]);
        //     } else {
        //         $open_ai = new OpenAi(config('services.openai.key'));
        //     }
        // }

        // if (config('settings.personal_openai_api') == 'allow') {
        //     $api_key = auth()->user()->personal_openai_key;        
        // } elseif (!is_null(auth()->user()->plan_id)) {
        //     $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
        //     if ($check_api->personal_openai_api) {
        //         $api_key = auth()->user()->personal_openai_key;                 
        //     } else {
        //         if (config('settings.openai_key_usage') !== 'main') {
        //                $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
        //             array_push($api_keys, config('services.openai.key'));
        //             $key = array_rand($api_keys, 1);
        //             $api_key = $api_keys[$key];
        //         } else {
        //             $api_key = config('services.openai.key');
        //         }
        //     }
        // } else {
        //     if (config('settings.openai_key_usage') !== 'main') {
        //         $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
        //         array_push($api_keys, config('services.openai.key'));
        //         $key = array_rand($api_keys, 1);
        //         $api_key = $api_keys[$key];
        //     } else {
        //         $api_key = config('services.openai.key');
        //     }
        // }

        $this->client = \OpenAI::factory()
            ->withApiKey(config('services.openai.key'))
            ->withHttpHeader('OpenAI-Beta', 'assistants=v1')
            ->make();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CustomChat::where('user_id', auth()->user()->id)->where('type', 'private')->orderBy('group', 'asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>      
                                    <a href="'. route("user.chat.custom.show", $row["id"] ). '"><i class="fa fa-edit table-action-buttons view-action-button" title="'. __('Update Chat Assistant') .'"></i></a>      
                                    <a class="activateButton" id="' . $row["id"] . '" type="' . $row['type'] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="'. __('Activate Chat Assistant') .'"></i></a>
                                    <a class="deactivateButton" id="' . $row["id"] . '" type="' . $row['type'] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="'. __('Deactivate Chat Assistant') .'"></i></a>  
                                    <a class="deleteTemplate" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Chat Assistant') .'"></i></a> 
                                </div>';
                    
                    return $actionBtn;
                })
                ->addColumn('updated-on', function($row){
                    $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd/m/Y').'</span><br><span>'.date_format($row["created_at"], 'H:i A').'</span>';
                    return $created_on;
                })
                ->addColumn('custom-name', function($row){
                    $user = '<div class="d-flex">
                                <div class="widget-user-name pt-1"><span class="font-weight-bold">'. $row['name'] .'</span></div>
                            </div>';
                    return $user;
                }) 
                ->addColumn('custom-status', function($row){
                    $status = ($row['status']) ? __('Active') : __('Deactive');
                    $custom_voice = '<span class="cell-box status-'.strtolower($status).'">'. $status.'</span>';
                    return $custom_voice;
                })
                ->rawColumns(['actions', 'updated-on', 'custom-name', 'custom-status'])
                ->make(true);
                    
        }

        $categories = ChatCategory::orderBy('name', 'asc')->get();

        return view('user.chat.custom.index', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'instructions' => 'required',
        ]);    
        
        $status = (isset($request->activate)) ? true : false;
        $retrieval = (isset($request->retrieval)) ? true : false;
        $code = (isset($request->code)) ? true : false;
        $has_file = false;

        if ($retrieval && $code) {
            $tools = [
                [ 'type' => 'code_interpreter'],
                [ 'type' => 'retrieval' ]
            ];
        } elseif ($retrieval) {
            $tools = [
                [ 'type' => 'retrieval' ]
            ];
        } elseif ($code) {
            $tools = [
                [ 'type' => 'code_interpreter'],
            ];
        } else {
            $tools = [];
        }

        if (request()->has('logo')) {
            
            $image = request()->file('logo');

            $name = Str::random(20);
            
            $folder = '/chats/custom/';
            
            $avatarPath = $folder . $name . '.' . $image->getClientOriginalExtension();

            $imageTypes = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(Str::lower($image->getClientOriginalExtension()), $imageTypes)) {
                toastr()->error(__('Chat avatar image must be in png, jpeg or webp formats'));
                return redirect()->back();
            } else {
                $this->uploadImage($image, $folder, 'public', $name);
            }
            
        } else {
            $avatarPath = '/chats/custom/avatar.webp';
        }

        if (request()->has('file')) {

            $file = request()->file('file');

            $imageTypes = ['c', 'cpp', 'docx', 'html', 'java', 'md', 'php', 'pptx', 'py', 'rb', 'tex', 'css', 'js', 'gif', 'tar', 'ts', 'xlsx', 'xml', 'zip', 'pdf', 'csv', 'txt', 'json'];
            if (!in_array(Str::lower($file->getClientOriginalExtension()), $imageTypes)) {
                toastr()->error(__('Uploaded training files must be in pdf, csv, json, jsonl or txt formats'));
                return redirect()->back();
            } else {

                $path = $request->file('file')->getRealPath();
                
                $uploaded_file = $this->client->files()->upload([
                    'purpose' => 'assistants',
                    'file' => fopen($path, 'r'),
                ]);

                $has_file = true;
            }
        
        }

        if ($has_file) {
            $response = $this->client->assistants()->create([
                'instructions' => $request->instructions,
                'name' => $request->name,
                'tools' => $tools,
                'file_ids' => [
                    $uploaded_file->id
                ],
                'model' => 'gpt-3.5-turbo-0125',
            ]);
        } else {
            $response = $this->client->assistants()->create([
                'instructions' => $request->instructions,
                'name' => $request->name,
                'tools' => $tools,
                'model' => 'gpt-3.5-turbo-0125',
            ]);
        }

        $template = new CustomChat([
            'user_id' => auth()->user()->id,
            'description' => $request->character,
            'status' => $status,
            'chat_code' => $response->id,
            'name' => $request->name,
            'group' => $request->group,
            'prompt' => $request->instructions,
            'sub_name' => $request->sub_name,
            'logo' => $avatarPath,
            'model' => 'gpt-3.5-turbo-0125',
            'code_interpreter' => $code,
            'retrieval' => $retrieval,
        ]); 
        
        $template->save();            

        toastr()->success(__('Custom Chat Assistant was successfully created'));
        return redirect()->back();       
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomChat $id)
    {
        if ($id->user_id == auth()->user()->id) {
            $categories = ChatCategory::orderBy('name', 'asc')->get();

            return view('user.chat.custom.edit', compact('id', 'categories'));
        } else {
            toastr()->warning(__('Access denied'));
            return redirect()->back();     
        }
        
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomChat $id)
    {        
        request()->validate([
            'name' => 'required',
            'instructions' => 'required',
        ]); 

        $status = (isset($request->activate)) ? true : false;
        $retrieval = (isset($request->retrieval)) ? true : false;
        $code = (isset($request->code)) ? true : false;
        $has_file = false;

        if ($retrieval && $code) {
            $tools = [
                [ 'type' => 'code_interpreter'],
                [ 'type' => 'retrieval' ]
            ];
        } elseif ($retrieval) {
            $tools = [
                [ 'type' => 'retrieval' ]
            ];
        } elseif ($code) {
            $tools = [
                [ 'type' => 'code_interpreter'],
            ];
        } else {
            $tools = [];
        }

        if (request()->has('logo')) {
            
            $image = request()->file('logo');

            $name = Str::random(20);
            
            $folder = '/chats/custom/';
            
            $avatarPath = $folder . $name . '.' . $image->getClientOriginalExtension();

            $imageTypes = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(Str::lower($image->getClientOriginalExtension()), $imageTypes)) {
                toastr()->error(__('Chat avatar image must be in png, jpeg or webp formats'));
                return redirect()->back();
            } else {
                $this->uploadImage($image, $folder, 'public', $name);
            }
            
        } else {
            $avatarPath = '/chats/custom/avatar.webp';
        }

        if (request()->has('file')) {

            $file = request()->file('file');

            $imageTypes = ['c', 'cpp', 'docx', 'html', 'java', 'md', 'php', 'pptx', 'py', 'rb', 'tex', 'css', 'js', 'gif', 'tar', 'ts', 'xlsx', 'xml', 'zip', 'pdf', 'csv', 'txt', 'json'];
            if (!in_array(Str::lower($file->getClientOriginalExtension()), $imageTypes)) {
                toastr()->error(__('Uploaded training files must be in pdf, csv, json, jsonl or txt formats'));
                return redirect()->back();
            } else {

                $path = $request->file('file')->getRealPath();
                
                $uploaded_file = $this->client->files()->upload([
                    'purpose' => 'assistants',
                    'file' => fopen($path, 'r'),
                ]);

                $has_file = true;
            }
        
        }

        $response = $this->client->assistants()->modify($id->chat_code, [
            'name' => $request->name,
            'instructions' => $request->instructions,
            'tools' => $tools,
        ]);

        if ($has_file) {
            $response = $this->client->assistants()->files()->create($id->chat_code, [
                'file_id' => $uploaded_file->id,
            ]);
        } 
    

        if ($id->user_id == auth()->user()->id) {

            $id->update([
                'description' => $request->character,
                'status' => $status,
                'name' => $request->name,
                'group' => $request->group,
                'prompt' => $request->instructions,
                'logo' => $avatarPath,
                'code_interpreter' => $code,
                'retrieval' => $retrieval,
                'sub_name' => $request->sub_name,
            ]); 

            toastr()->success(__('Custom Chat Assistant was successfully updated'));
            return redirect()->route('user.chat.custom');
        } else {
            toastr()->warning(__('Access denied'));
            return redirect()->back();   
        }

    }


    /**
     * Activate template
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chatActivate(Request $request)
    {
        if ($request->ajax()) {

            $template = CustomChat::where('id', request('id'))->firstOrFail();

            if ($template->user_id == auth()->user()->id) {
                if ($template->status == true) {
                    return  response()->json(true);
                }

                $template->update(['status' => true]);

                return  response()->json('success');

            } else {
                return response()->json('error');
            }
        }
    }


    /**
     * Deactivate template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chatDeactivate(Request $request)
    {
        if ($request->ajax()) {

            $template = CustomChat::where('id', request('id'))->firstOrFail();

            if ($template->user_id == auth()->user()->id) {
                if ($template->status == false) {
                    return  response()->json(false);
                }
    
                $template->update(['status' => false]);
    
                return  response()->json('success');
            } else {
                return response()->json('error');
            }
            
        }
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chatDelete(Request $request)
    {
        if ($request->ajax()) {

            $result = CustomChat::where('id', request('id'))->firstOrFail();  

            if ($result->user_id == auth()->user()->id){

                $result->delete();

                return response()->json('success');    
    
            } else{
                return response()->json('error');
            } 
        }              
    }


     /**
     * Upload user profile image
     */
    public function uploadImage(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $image = $file->storeAs($folder, $name .'.'. $file->getClientOriginalExtension(), $disk);

        return $image;
    }





}
