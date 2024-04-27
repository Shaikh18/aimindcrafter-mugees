<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use App\Models\SubscriptionPlan;
use App\Models\FavoriteChat;
use App\Models\ChatConversation;
use App\Models\ChatCategory;
use App\Models\ChatHistory;
use App\Models\ChatPrompt;
use App\Models\ApiKey;
use App\Models\Chat;
use App\Models\User;
use GuzzleHttp\Client;


class ChatImageController extends Controller
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
        # Check user permission to use the feature
        if (auth()->user()->group == 'user') {
            if (config('settings.chat_image_user_access') != 'allow') {
               toastr()->warning(__('Chat Image feature is not available for free tier users, subscribe to get a proper access'));
               return redirect()->route('user.plans');
            } else {
                if (session()->has('conversation_id')) {
                    session()->forget('conversation_id');
                }
        
                $chat = Chat::where('chat_code', 'IMAGE')->first(); 
                $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', 'IMAGE')->orderBy('updated_at', 'desc')->get(); 
        
                $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
                $prompts = ChatPrompt::where('status', true)->get();
        
                return view('user.chat_image.index', compact('chat', 'messages', 'categories', 'prompts'));
            }
        } elseif (auth()->user()->group == 'subscriber') {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan->chat_image_feature == false) {     
                toastr()->warning(__('Your current subscription plan does not include support for Chat Image feature'));
                return redirect()->back();                   
            } else {
                if (session()->has('conversation_id')) {
                    session()->forget('conversation_id');
                }
        
                $chat = Chat::where('chat_code', 'IMAGE')->first(); 
                $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', 'IMAGE')->orderBy('updated_at', 'desc')->get(); 
        
                $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
                $prompts = ChatPrompt::where('status', true)->get();
        
                return view('user.chat_image.index', compact('chat', 'messages', 'categories', 'prompts'));
            }
        } else {
            if (session()->has('conversation_id')) {
                session()->forget('conversation_id');
            }
    
            $chat = Chat::where('chat_code', 'IMAGE')->first(); 
            $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', 'IMAGE')->orderBy('updated_at', 'desc')->get(); 
    
            $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
            $prompts = ChatPrompt::where('status', true)->get();
    
            return view('user.chat_image.index', compact('chat', 'messages', 'categories', 'prompts'));
        }
    }


    /**
	*
	* Process Input Text
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function process(Request $request) 
    {       
        if ($request->ajax()) {

            # Check personal API keys
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


            # Check if user has sufficient words available to proceed
            if (auth()->user()->available_dalle_images != -1) {
                if ((auth()->user()->available_dalle_images + auth()->user()->available_dalle_images_prepaid) <= 0) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_image) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_dalle_images + $member->available_dalle_images_prepaid) <= 0) {
                                $data['status'] = 'error';
                                $data['message'] = __('Not enough Dalle image balance to proceed, subscribe or top up your image balance and try again');
                                return $data;
                            }
                        } else {
                            $data['status'] = 'error';
                            $data['message'] = __('Not enough Dalle image balance to proceed, subscribe or top up your image balance and try again');
                            return $data;
                        }
                        
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = __('Not enough Dalle image balance to proceed, subscribe or top up your image balance and try again');
                        return $data;
                    } 
                }
            }

            $chat = new ChatHistory();
            $chat->user_id = auth()->user()->id;
            $chat->conversation_id = $request->conversation_id;
            $chat->prompt = $request->input('message');
            $chat->save();

            $complete = $open_ai->image([
                'model' => 'dall-e-3',
                'prompt' => $request->input('message'),
                'size' => '1024x1024',
                'n' => 1,
                "response_format" => "url",
                'quality' => "standard",
            ]);

            $response = json_decode($complete , true);

            if (isset($response['data'])) {

                $url = $response['data'][0]['url'];

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_URL, $url);
                $contents = curl_exec($curl);
                curl_close($curl);


                $name = 'chat-image-' . Str::random(10) . '.png';

                if (config('settings.default_storage') == 'local') {
                    Storage::disk('public')->put('images/' . $name, $contents);
                    $image_url = 'images/' . $name;
                    $image_url = URL::asset($image_url);
                } elseif (config('settings.default_storage') == 'aws') {
                    Storage::disk('s3')->put('images/' . $name, $contents, 'public');
                    $image_url = Storage::disk('s3')->url('images/' . $name);
                } elseif (config('settings.default_storage') == 'r2') {
                    Storage::disk('r2')->put('images/' . $name, $contents, 'public');
                    $image_url = Storage::disk('r2')->url('images/' . $name);
                } elseif (config('settings.default_storage') == 'wasabi') {
                    Storage::disk('wasabi')->put('images/' . $name, $contents);
                    $image_url = Storage::disk('wasabi')->url('images/' . $name);
                }

                $chat->response = $image_url;
                $chat->words = 0;
                $chat->save();

                # Update credit balance
                $this->updateBalance(1);  

                $chat_conversation = ChatConversation::where('conversation_id', $request->conversation_id)->first(); 
                $chat_conversation->words = 0;
                $chat_conversation->messages = $chat_conversation->messages + 1;
                $chat_conversation->save();

        
                $data['status'] = 'success';
                $data['url'] = $image_url;
                $data['old'] = auth()->user()->available_dalle_images + auth()->user()->available_dalle_images_prepaid;
                $data['current'] = auth()->user()->available_dalle_images + auth()->user()->available_dalle_images_prepaid - 1;
                $data['balance'] = (auth()->user()->available_dalle_images == -1) ? 'unlimited' : 'counted';
                return $data; 

            } else {
                if ($response['error']['code'] == 'invalid_api_key') {
                    $message = 'Please try again, Dalle 3 model limit has been reached for today.';
                } else {
                    $message = $response['error']['message'];
                }    

                $data['status'] = 'error';
                $data['message'] = $message;
                return $data;
            }
        }

	}


    /**
	*
	* Clear Session
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function clear(Request $request) 
    {
        if (session()->has('conversation_id')) {
            session()->forget('conversation_id');
        }

        return response()->json(['status' => 'success']);
	}



    /**
	*
	* Update user word balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalance($images) {

        $user = User::find(Auth::user()->id);

        if (auth()->user()->available_dalle_images != -1) {
        
            if (Auth::user()->available_dalle_images > $images) {

                $total_images = Auth::user()->available_dalle_images - $images;
                $user->available_dalle_images = ($total_images < 0) ? 0 : $total_images;

            } elseif (Auth::user()->available_dalle_images_prepaid > $images) {

                $total_images_prepaid = Auth::user()->available_dalle_images_prepaid - $images;
                $user->available_dalle_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;

            } elseif ((Auth::user()->available_dalle_images + Auth::user()->available_idalle_mages_prepaid) == $images) {

                $user->available_dalle_images = 0;
                $user->available_dalle_images_prepaid = 0;

            } else {

                if (!is_null(Auth::user()->member_of)) {

                    $member = User::where('id', Auth::user()->member_of)->first();

                    if ($member->available_dalle_images > $images) {

                        $total_images = $member->available_dalle_images - $images;
                        $member->available_dalle_images = ($total_images < 0) ? 0 : $total_images;
            
                    } elseif ($member->available_dalle_images_prepaid > $images) {
            
                        $total_images_prepaid = $member->available_dalle_images_prepaid - $images;
                        $member->available_dalle_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;
            
                    } elseif (($member->available_dalle_images + $member->available_dalle_images_prepaid) == $images) {
            
                        $member->available_dalle_images = 0;
                        $member->available_dalle_images_prepaid = 0;
            
                    } else {
                        $remaining = $images - $member->available_dalle_images;
                        $member->available_dalle_images = 0;
        
                        $prepaid_left = $member->available_dalle_images_prepaid - $remaining;
                        $member->available_dalle_images_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    }

                    $member->update();

                } else {
                    $remaining = $images - Auth::user()->available_dalle_images;
                    $user->available_dalle_images = 0;

                    $prepaid_left = Auth::user()->available_dalle_images_prepaid - $remaining;
                    $user->available_dalle_images_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                }
            }
        }

        $user->update();

        return true;
    }


    /**
	*
	* Chat conversation
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function conversation(Request $request) {

        if ($request->ajax()) {

            $chat = new ChatConversation();
            $chat->user_id = auth()->user()->id;
            $chat->title = 'Chat Image Conversation';
            $chat->chat_code = $request->chat_code;
            $chat->conversation_id = $request->conversation_id;
            $chat->messages = 0;
            $chat->words = 0;
            $chat->save();

            $data = 'success';
            return $data;
        }   
    }


    /**
	*
	* Chat history
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function history(Request $request) {

        if ($request->ajax()) {

            $messages = ChatHistory::where('user_id', auth()->user()->id)->where('conversation_id', $request->conversation_id)->get();
            return $messages;
        }   
    }


    /**
	*
	* Rename conversation
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function rename(Request $request) 
    {
        if ($request->ajax()) {

            $chat = ChatConversation::where('conversation_id', request('conversation_id'))->first(); 

            if ($chat) {
                if ($chat->user_id == auth()->user()->id){

                    $chat->title = request('name');
                    $chat->save();
    
                    $data['status'] = 'success';
                    $data['conversation_id'] = request('conversation_id');
                    return $data;  
        
                } else{
    
                    $data['status'] = 'error';
                    $data['message'] = __('There was an error while changing the conversation title');
                    return $data;
                }
            } 
              
        }
	}


    /**
	*
	* Delete chat
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function delete(Request $request) 
    {
        if ($request->ajax()) {

            $chat = ChatConversation::where('conversation_id', request('conversation_id'))->first(); 

            if ($chat) {
                if ($chat->user_id == auth()->user()->id){

                    $chat->delete();

                    if (session()->has('conversation_id')) {
                        session()->forget('conversation_id');
                    }
    
                    $data['status'] = 'success';
                    return $data;  
        
                } else{
    
                    $data['status'] = 'error';
                    $data['message'] = __('There was an error while deleting the chat history');
                    return $data;
                }
            } else {
                $data['status'] = 'empty';
                return $data;
            }
              
        }
	}


}
