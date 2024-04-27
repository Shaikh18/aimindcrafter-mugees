<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ServerEvent;
use App\Models\ChatSpecial;
use App\Models\User;
use App\Models\ChatPrompt;
use App\Models\ChatHistorySpecial;
use App\Models\SubscriptionPlan;
use App\Services\QueryEmbedding;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZanySoft\Zip\Zip;

class ChatFileController extends Controller
{

    protected QueryEmbedding $query;

    public function __construct(QueryEmbedding $query)
    {
        $this->query = $query;
    }

    public function index()
    {
        $chats = ChatSpecial::where('user_id', auth()->user()->id)->where('type', '<>', 'web')->orderBy('created_at', 'desc')->get();
        $first_chat = ChatSpecial::where('user_id', auth()->user()->id)->where('type', '<>', 'web')->first();
        $chat_code = ($first_chat) ? $first_chat->id : 'new';
        $prompts = ChatPrompt::where('status', true)->get();

        if (auth()->user()->group == 'user') {
            if (config('settings.chat_file_user_access') != 'allow') {
                toastr()->warning(__('AI Chat File feature is not available for free tier users, subscribe to get a proper access'));
                return redirect()->route('user.plans');
            } else {
                $pdf_limit = config('settings.chat_pdf_file_size_user');
                $csv_limit = config('settings.chat_csv_file_size_user');
                $word_limit = config('settings.chat_word_file_size_user');
                return view('user.chat_file.index', compact('chats', 'chat_code', 'prompts', 'pdf_limit', 'csv_limit', 'word_limit'));
            }
        } elseif (auth()->user()->group == 'subscriber') {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan->file_chat_feature == false) {     
                toastr()->warning(__('Your current subscription plan does not include support for AI Chat File feature'));
                return redirect()->back();                   
            } else {
                $pdf_limit = config('settings.chat_pdf_file_size_user');
                $csv_limit = config('settings.chat_csv_file_size_user');
                $word_limit = config('settings.chat_word_file_size_user');
                return view('user.chat_file.index', compact('chats', 'chat_code', 'prompts', 'pdf_limit', 'csv_limit', 'word_limit'));
            }
        } else {
            $pdf_limit = config('settings.chat_pdf_file_size_user');
            $csv_limit = config('settings.chat_csv_file_size_user');
            $word_limit = config('settings.chat_word_file_size_user');
            return view('user.chat_file.index', compact('chats', 'chat_code', 'prompts', 'pdf_limit', 'csv_limit', 'word_limit'));
        }
   
    }

    public function conversation(Request $request)
    {
        if ($request->ajax()) {

            $messages = ChatHistorySpecial::where('user_id', auth()->user()->id)->where('chat_special_id', $request->chat_id)->get();

            $data['messages'] = $messages;

            return $data;
        }  
    }

    public function process(Request $request)
    {
        return response()->stream(function () use ($request) {
            try {
                $chat = ChatSpecial::where('user_id', auth()->user()->id)->where('id', $request->chat_id)->first();
                $question = $request->message;
                $queryVectors = $this->query->getQueryEmbedding($question);
                $vector = json_encode($queryVectors);
                $result = DB::table('embeddings')
                    ->select("text")
                    ->selectSub("embedding <=> '{$vector}'", "distance")
                    ->where('embedding_collection_id', $chat->embedding_collection->id)
                    ->orderBy('distance', 'asc')
                    ->limit(3)
                    ->get();
                $context = collect($result)->map(function ($item) {
                    return $item->text;
                })->implode("\n");

                $stream = $this->query->askQuestionStreamed($context, $question);
                $resultText = "";
                foreach ($stream as $response) {
                    $text = $response->choices[0]->delta->content;
                    $resultText .= $text;
                    if (connection_aborted()) {
                        break;
                    }
                    ServerEvent::send($text, "");
                }

                ChatHistorySpecial::insert([[
                    'chat_special_id' => $request->chat_id,
                    'role' => ChatHistorySpecial::ROLE_USER,
                    'content' => $question, 
                    'user_id' => auth()->user()->id
                ], [
                    'chat_special_id' => $request->chat_id,
                    'role' => ChatHistorySpecial::ROLE_BOT,
                    'content' => $resultText,
                    'user_id' => auth()->user()->id
                ]]);

                $chat->messages = $chat->messages + 1;
                $chat->save();

                $words = count(explode(' ', ($resultText)));
                $this->updateBalance($words);

            } catch (Exception $e) {
                Log::error($e);
                ServerEvent::send("");
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
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

            $chat = ChatSpecial::where('id', request('chat_id'))->first(); 

            if ($chat) {
                if ($chat->user_id == auth()->user()->id){

                    $chat->title = request('name');
                    $chat->save();
    
                    $data['status'] = 'success';
                    $data['chat_id'] = request('chat_id');
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

            $chat = ChatSpecial::where('id', request('chat_id'))->first(); 

            if ($chat) {
                if ($chat->user_id == auth()->user()->id){

                    $chat->delete();
    
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


    public function checkBalance(Request $request)
    {
        # Check personal API keys
        if (config('settings.personal_openai_api') == 'allow') {
            if (is_null(auth()->user()->personal_openai_key)) {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                return $data;
            }     
        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_openai_api) {
                if (is_null(auth()->user()->personal_openai_key)) {
                    $data['status'] = 'error';
                    $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                    return $data;
                } 
            }    
        } 

        if ($request->task == 'process') {
            $words = count(explode(' ', ($request->message)));

            # Verify if user has enough credits
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $words) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_template) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_words + $member->available_words_prepaid) < $words) {
                                $data['status'] = 'error';
                                $data['message'] = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                                return $data;
                            }
                        } else {
                            $data['status'] = 'error';
                            $data['message'] = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                            return $data;
                        }
                        
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                        return $data;
                    } 
                } else {
                    $data['status'] = 'success';
                    return $data;
                }
            } else {
                $data['status'] = 'success';
                return $data;
            }
        } else {
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < 100) { 
                    $data['status'] = 'error';
                    $data['message'] = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                    return $data;
                } else {
                    $data['status'] = 'success';
                    return $data;
                }
            } else {
                $data['status'] = 'success';
                return $data;
            }   
        }        
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


    public function metainfo(Request $request)
    {
        if ($request->ajax()) {
            $chat = ChatSpecial::where('id', $request->chat_id)->first();

            if ($chat) {
                $data['status'] = 'success';
                $data['id'] = $chat->id;
                $data['title'] = $chat->title;
                $data['url'] = $chat->url;

                return $data;
            } else {
                $data['status'] = 'error';

                return $data;
            }
        }
        
    }


    public function credits(Request $request) 
    {
        if ($request->ajax()) {

            if (auth()->user()->available_words == -1) {
                $data['credits'] = 'Unlimited';
                return $data;
            } else {
                $data['credits'] = Auth::user()->available_words + Auth::user()->available_words_prepaid;
                return $data;
            }              
        }
	}

}
