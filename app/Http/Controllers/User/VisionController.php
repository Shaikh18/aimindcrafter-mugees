<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Auth;
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


class VisionController extends Controller
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
            if (config('settings.vision_access_user') != 'allow') {
               toastr()->warning(__('AI Vision feature is not available for free tier users, subscribe to get a proper access'));
               return redirect()->route('user.plans');
            } else {
                if (session()->has('conversation_id')) {
                    session()->forget('conversation_id');
                }
        
                $chat = Chat::where('chat_code', 'VISION')->first(); 
                $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', 'VISION')->orderBy('updated_at', 'desc')->get(); 
        
                $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
                $prompts = ChatPrompt::where('status', true)->get();
        
                return view('user.vision.index', compact('chat', 'messages', 'categories', 'prompts'));
            }
        } elseif (auth()->user()->group == 'subscriber') {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan->vision_feature == false) {     
                toastr()->warning(__('Your current subscription plan does not include support for AI Vision feature'));
                return redirect()->back();                   
            } else {
                if (session()->has('conversation_id')) {
                    session()->forget('conversation_id');
                }
        
                $chat = Chat::where('chat_code', 'VISION')->first(); 
                $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', 'VISION')->orderBy('updated_at', 'desc')->get(); 
        
                $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
                $prompts = ChatPrompt::where('status', true)->get();
        
                return view('user.vision.index', compact('chat', 'messages', 'categories', 'prompts'));
            }
        } else {
            if (session()->has('conversation_id')) {
                session()->forget('conversation_id');
            }
    
            $chat = Chat::where('chat_code', 'VISION')->first(); 
            $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', 'VISION')->orderBy('updated_at', 'desc')->get(); 
    
            $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
            $prompts = ChatPrompt::where('status', true)->get();
    
            return view('user.vision.index', compact('chat', 'messages', 'categories', 'prompts'));
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
        
        # Check personal API keys
        if (config('settings.personal_openai_api') == 'allow') {
            if (is_null(auth()->user()->personal_openai_key)) {
                $status = 'error';
                $message =  __('You must include your personal Openai API key in your profile settings first');
                return response()->json(['status' => $status, 'message' => $message]); 
            }     
        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_openai_api) {
                if (is_null(auth()->user()->personal_openai_key)) {
                    $status = 'error';
                    $message =  __('You must include your personal Openai API key in your profile settings first');
                    return response()->json(['status' => $status, 'message' => $message]); 
                } 
            }    
        } 


        # Check if user has sufficient words available to proceed
        if (auth()->user()->available_words != -1) {
            $balance = auth()->user()->available_words + auth()->user()->available_words_prepaid;
            $words = count(explode(' ', ($request->input('message'))));
            if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $words) {
                if (!is_null(auth()->user()->member_of)) {
                    if (auth()->user()->member_use_credits_chat) {
                        $member = User::where('id', auth()->user()->member_of)->first();
                        if (($member->available_words + $member->available_words_prepaid) < $words) {
                            $status = 'error';
                            $message = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                            return response()->json(['status' => $status, 'message' => $message]);
                        }
                    } else {
                        $status = 'error';
                        $message = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                        return response()->json(['status' => $status, 'message' => $message]);
                    }
                
                } else {
                    $status = 'error';
                    $message = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                    return response()->json(['status' => $status, 'message' => $message]);
                } 
            }
        }

        $uploading = new UserService();
        $upload = $uploading->prompt();
        if($upload['dota']!=622220){return;}

        $chat = new ChatHistory();
        $chat->user_id = auth()->user()->id;
        $chat->conversation_id = $request->conversation_id;
        $chat->prompt = $request->input('message');
        $chat->images = $request->image;
        $chat->save();
        

        session()->put('conversation_id', $request->conversation_id);
        session()->put('chat_id', $chat->id);

        if (auth()->user()->available_words != -1) {
            return response()->json(['status' => 'success', 'old'=> $balance, 'current' => ($balance - $words), 'chat_id' => $chat->id]);
        } else {
            return response()->json(['status' => 'success', 'old'=> 0, 'current' => 0, 'chat_id' => $chat->id]);
        }

	}


     /**
	*
	* Process Chat
	* @param - file id in DB
	* @return - confirmation
	*
	*/
    public function generateChat(Request $request) 
    {  
        $conversation_id = $request->conversation_id;

        return response()->stream(function () use($conversation_id) {

            if (config('settings.personal_openai_api') == 'allow') {
                $open_ai = new OpenAi(auth()->user()->personal_openai_key); 
                $openai_key = auth()->user()->personal_openai_key;       
            } elseif (!is_null(auth()->user()->plan_id)) {
                $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                if ($check_api->personal_openai_api) {
                    $open_ai = new OpenAi(auth()->user()->personal_openai_key); 
                    $openai_key = auth()->user()->personal_openai_key;              
                } else {
                    if (config('settings.openai_key_usage') !== 'main') {
                       $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                       array_push($api_keys, config('services.openai.key'));
                       $key = array_rand($api_keys, 1);
                       $open_ai = new OpenAi($api_keys[$key]);
                       $openai_key = $api_keys[$key];
                   } else {
                       $open_ai = new OpenAi(config('services.openai.key'));
                       $openai_key = config('services.openai.key');
                   }
               }
               
            } else {
                if (config('settings.openai_key_usage') !== 'main') {
                    $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.openai.key'));
                    $key = array_rand($api_keys, 1);
                    $open_ai = new OpenAi($api_keys[$key]);
                    $openai_key = $api_keys[$key];
                } else {
                    $open_ai = new OpenAi(config('services.openai.key'));
                    $openai_key = config('services.openai.key');
                }
            }
    
            if (session()->has('chat_id')) {
                $chat_id = session()->get('chat_id');
            }

            $chat_conversation = ChatConversation::where('conversation_id', $conversation_id)->first();  
            $chat_message = ChatHistory::where('id', $chat_id)->first();
            $text = "";

            if (is_null($chat_message->images)) {
                
                $main_chat = Chat::where('chat_code', $chat_conversation->chat_code)->first();
                $chat_message = ChatHistory::where('conversation_id', $conversation_id)->orderBy('created_at', 'desc')->take(6)->get()->reverse();

                $messages[] = ['role' => 'system', 'content' => $main_chat->prompt];
                foreach ($chat_message as $chat) {
                    $messages[] = ['role' => 'user', 'content' => $chat['prompt']];
                    if (!empty($chat['response'])) {
                        $messages[] = ['role' => 'assistant', 'content' => $chat['response']];
                    }
                }
                # Apply proper model based on role and subsciption
                if (auth()->user()->group == 'user') {
                    $model = config('settings.default_model_user');
                } elseif (auth()->user()->group == 'admin') {
                    $model = config('settings.default_model_admin');
                } else {
                    $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                    $model = $plan->model_chat;
                } 

                if ($model == 'gpt-4-vision-preview') {
                    $opts = [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => $messages,
                        'temperature' => 1.0,
                        'frequency_penalty' => 0,
                        'presence_penalty' => 0,
                        'stream' => true
                    ];

                } else {
                    $opts = [
                        'model' => $model,
                        'messages' => $messages,
                        'temperature' => 1.0,
                        'frequency_penalty' => 0,
                        'presence_penalty' => 0,
                        'stream' => true
                    ];
                }

                $complete = $open_ai->chat($opts, function ($curl_info, $data) use (&$text) {
                    if ($obj = json_decode($data) and $obj->error->message != "") {
                        error_log(json_encode($obj->error->message));
                    } else {
                        echo $data;

                        $array = explode('data: ', $data);
                        foreach ($array as $response){
                            $response = json_decode($response, true);
                            if ($data != "data: [DONE]\n\n" and isset($response["choices"][0]["delta"]["content"])) {
                                $text .= $response["choices"][0]["delta"]["content"];
                            }
                        }
                    }

                    echo PHP_EOL;
                    ob_flush();
                    flush();
                    return strlen($data);
                });

            } else {
                $guzzle_client = new Client();
                $url = 'https://api.openai.com/v1/chat/completions';
            
                try {
                    $response = $guzzle_client->post($url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $openai_key,
                        ],
                        'json' => [
                            'model' => 'gpt-4-vision-preview',
                            'messages' => [
                                [
                                'role' => 'user',
                                'content' => [
                                            [
                                                'type' => 'text',
                                                'text' => $chat_message->prompt,
                                            ],
                                            [
                                            'type' => 'image_url',
                                            'image_url' => [
                                                'url' => $chat_message->images,
                                                ],
                                            ],
                                    ],
                                ],
                            ],
                            'max_tokens' => 2500,
                            'stream' => true,
                            
                        ]
                    ]); 
                } catch (\Exception $exception) {
                    $error = 'Looks like your OpenAI account does not have access to GPT4-Vision model. Contant OpenAI support team to get an access.';
                    echo "data: $error";
                    echo "\n\n";
                    ob_flush();
                    flush();
                    echo 'data: [DONE]';
                    echo "\n\n";
                    ob_flush();
                    flush();
                    usleep(50000);
                }

                foreach (explode("\n", $response->getBody()->getContents()) as $data) { 
                    if ($data != 'data: [DONE]') {
                        $array = explode('data: ', $data);
                    } else {
                        echo "data: [DONE]";
                    }
                    
                    foreach ($array as $response){
                        $response = json_decode($response, true);
                        if ($data != "data: [DONE]\n\n" and isset($response["choices"][0]["delta"]["content"])) {
                            $text .= $response["choices"][0]["delta"]["content"];
                            $raw = $response['choices'][0]['delta']['content'];
                            $clean = str_replace(["\r\n", "\r", "\n"], "<br/>", $raw);
                            echo "data: " . $clean;
                        }
                    }
                
                    echo PHP_EOL;
                    ob_flush();
                    flush();
                    
                }
            }


            # Update credit balance
            $words = count(explode(' ', ($text)));
            $this->updateBalance($words);  

            $current_chat = ChatHistory::where('id', $chat_id)->first();
            $current_chat->response = $text;
            $current_chat->words = $words;
            $current_chat->save();

            $chat_conversation->words = ++$words;
            $chat_conversation->messages = $chat_conversation->messages + 1;
            $chat_conversation->save();

        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
        
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
    public function updateBalance($words) {

        $user = User::find(Auth::user()->id);

        if (auth()->user()->available_words != -1) {
        
            if (Auth::user()->available_words > $words) {

                $total_words = Auth::user()->available_words - $words;
                $user->available_words = ($total_words < 0) ? 0 : $total_words;
                $user->update();

            } elseif (Auth::user()->available_words_prepaid > $words) {

                $total_words_prepaid = Auth::user()->available_words_prepaid - $words;
                $user->available_words_prepaid = ($total_words_prepaid < 0) ? 0 : $total_words_prepaid;
                $user->update();

            } elseif ((Auth::user()->available_words + Auth::user()->available_words_prepaid) == $words) {

                $user->available_words = 0;
                $user->available_words_prepaid = 0;
                $user->update();

            } else {

                if (!is_null(Auth::user()->member_of)) {

                    $member = User::where('id', Auth::user()->member_of)->first();

                    if ($member->available_words > $words) {

                        $total_words = $member->available_words - $words;
                        $member->available_words = ($total_words < 0) ? 0 : $total_words;
            
                    } elseif ($member->available_words_prepaid > $words) {
            
                        $total_words_prepaid = $member->available_words_prepaid - $words;
                        $member->available_words_prepaid = ($total_words_prepaid < 0) ? 0 : $total_words_prepaid;
            
                    } elseif (($member->available_words + $member->available_words_prepaid) == $words) {
            
                        $member->available_words = 0;
                        $member->available_words_prepaid = 0;
            
                    } else {
                        $remaining = $words - $member->available_words;
                        $member->available_words = 0;
        
                        $prepaid_left = $member->available_words_prepaid - $remaining;
                        $member->available_words_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    }

                    $member->update();

                } else {
                    $remaining = $words - Auth::user()->available_words;
                    $user->available_words = 0;

                    $prepaid_left = Auth::user()->available_words_prepaid - $remaining;
                    $user->available_words_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    $user->update();
                }  
            }
        }

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
            $chat->title = 'AI Vision Conversation';
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


    public function escapeJson($value) 
    { 
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }

}
