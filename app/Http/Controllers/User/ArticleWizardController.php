<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Traits\VoiceToneTrait;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\SubscriptionPlan;
use App\Models\Template;
use App\Models\Content;
use App\Models\Workbook;
use App\Models\Language;
use App\Models\ApiKey;
use App\Models\User;
use App\Models\ArticleWizard;
use Exception;


class ArticleWizardController extends Controller
{
    use VoiceToneTrait;

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
            if (config('settings.wizard_access_user') != 'allow') {
               toastr()->warning(__('AI Article Wizard feature is not available for free tier users, subscribe to get a proper access'));
               return redirect()->route('user.plans');
            } else {
                $languages = Language::orderBy('languages.language', 'asc')->get();

                $workbooks = Workbook::where('user_id', auth()->user()->id)->latest()->get();

                $wizard = ArticleWizard::where('user_id', auth()->user()->id)->where('current_step', '!=', 5)->first();

                if (!$wizard) {
                    $wizard = new ArticleWizard();
                    $wizard->user_id = auth()->user()->id;
                    $wizard->save();
                }

                $wizard = ArticleWizard::find($wizard->id)->toArray();

                return view('user.templates.wizard.index', compact('languages', 'workbooks', 'wizard'));
            }
        } elseif (auth()->user()->group == 'subscriber') {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan->wizard_feature == false) {     
                toastr()->warning(__('Your current subscription plan does not include support for AI Article Wizard feature'));
                return redirect()->back();                   
            } else {
                $languages = Language::orderBy('languages.language', 'asc')->get();

                $workbooks = Workbook::where('user_id', auth()->user()->id)->latest()->get();

                $wizard = ArticleWizard::where('user_id', auth()->user()->id)->where('current_step', '!=', 5)->first();

                if (!$wizard) {
                    $wizard = new ArticleWizard();
                    $wizard->user_id = auth()->user()->id;
                    $wizard->save();
                }

                $wizard = ArticleWizard::find($wizard->id)->toArray();

                return view('user.templates.wizard.index', compact('languages', 'workbooks', 'wizard'));
            }
        } else {
            $languages = Language::orderBy('languages.language', 'asc')->get();

            $workbooks = Workbook::where('user_id', auth()->user()->id)->latest()->get();

            $wizard = ArticleWizard::where('user_id', auth()->user()->id)->where('current_step', '!=', 5)->first();

            if (!$wizard) {
                $wizard = new ArticleWizard();
                $wizard->user_id = auth()->user()->id;
                $wizard->save();
            }

            $wizard = ArticleWizard::find($wizard->id)->toArray();

            return view('user.templates.wizard.index', compact('languages', 'workbooks', 'wizard'));
        }
        
    }


    /**
	*
	* Generate keywords
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function keywords(Request $request) 
    {
        if ($request->ajax()) {
            $max_tokens = 100;

           
            # Check Openai APIs
            $key = $this->getOpenai();
            if ($key == 'none') {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                return $data; 
            }

            # Verify if user has enough credits
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $max_tokens) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_template) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_words + $member->available_words_prepaid) < $max_tokens) {
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
                }
            }
            $identify = $this->api->verify_license();
            if($identify['dota']!=622220){return false;}

            # Apply proper model based on role and subsciption
            if (auth()->user()->group == 'user') {
                $model = config('settings.default_model_user');
            } elseif (auth()->user()->group == 'admin') {
                $model = config('settings.default_model_admin');
            } else {
                $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                $model = $plan->model;
            }

            try {
                $response = OpenAI::chat()->create([
                    'model' => $model,
                    'messages' => [[
                        'role' => 'user',
                        'content' => "Generate $request->keywords_numbers keywords (simple words or 2 words, not phrase, not person name) about '$request->topic'. Must resut as a comma separated string without any extra details. Result format is: keyword1, keyword2, ..., keywordN. Must not write ```json."
                    ]]
                ]);

                # Update credit balance
                $words = count(explode(' ', ($response['choices'][0]['message']['content'])));
                $this->updateBalance($words); 

                $flag = Language::where('language_code', $request->language)->first();

                $wizard = ArticleWizard::where('id', $request->wizard)->first();
                if (is_null($wizard->keywords)) {
                    $wizard->keywords = $response['choices'][0]['message']['content'];
                } else {
                    $wizard->keywords .= ', ' . $response['choices'][0]['message']['content'];
                }          
                $wizard->language = $flag->language;          
                $wizard->tone = $request->tone;          
                $wizard->creativity = (float)$request->creativity;          
                $wizard->view_point = $request->view_point;          
                $wizard->max_words = $request->words;          
                $wizard->save();

                $data['old'] = auth()->user()->available_words + auth()->user()->available_words_prepaid;
                $data['current'] = auth()->user()->available_words + auth()->user()->available_words_prepaid - $words;
                $data['type'] = (auth()->user()->available_words == -1) ? 'unlimited' : 'counted';

                return response()->json(['result' => $response['choices'][0]['message']['content'], 'balance' => $data]);

            } catch (Exception $e) {
                $data['status'] = 'error';
                $data['message'] = __('There was an issue with keywords generation, please try again') . $e->getMessage();
                return $data; 
            }
        }
	}


    /**
	*
	* Generate ideas
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function ideas(Request $request) 
    {
        if ($request->ajax()) {
            $max_tokens = '';

           
            # Check Openai APIs
            $key = $this->getOpenai();
            if ($key == 'none') {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                return $data; 
            }

            # Verify if user has enough credits
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $max_tokens) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_template) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_words + $member->available_words_prepaid) < $max_tokens) {
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
                }
            }
            $identify = $this->api->verify_license();
            if($identify['dota']!=622220){return false;}

            # Apply proper model based on role and subsciption
            if (auth()->user()->group == 'user') {
                $model = config('settings.default_model_user');
            } elseif (auth()->user()->group == 'admin') {
                $model = config('settings.default_model_admin');
            } else {
                $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                $model = $plan->model;
            }

            try {

                if (!is_null($request->keywords) || $request->keywords != '') {
                    $prompt = "Generate $request->topics_number titles. Titles must be about topic:  $request->topic . Use following keywords in the titles: $request->keywords. (Without number for order). Must not write any description. Strictly create in array json data. Every title is sentence or phrase string. The depth is 1. This is result format: [title1, title2, ..., titlen]. Maximum title length is $request->topic_length. Must not write ```json.";
                } else {
                    $prompt = "Generate $request->topics_number titles. Titles must be about topic:  $request->topic . (Without number for order, titles are not keywords). Must not write any description. Strictly create in array json data. Every title is sentence or phrase string. The depth is 1. This is result format: [title1, title2, ..., titlen]. Maximum title length is $request->topic_length. Must not write ```json.";
                }

                $response = OpenAI::chat()->create([
                    'model' => $model,
                    'messages' => [[
                        'role' => 'user',
                        'content' => $prompt,
                    ]]
                ]);
                
                $result = json_decode($response['choices'][0]['message']['content']);
       
                $main_string = '';
                $numItems = count($result);
                $i = 0;
                foreach ($result as $key => $value) {
                    if (++$i == $numItems) {
                        $main_string .= $value;
                    } else {
                        $main_string .= $value . ', ';
                    }
                }

                # Update credit balance
                $words = count(explode(' ', ($response['choices'][0]['message']['content'])));
                $this->updateBalance($words); 

                $wizard = ArticleWizard::where('id', $request->wizard)->first();
                if (is_null($wizard->titles)) {
                    $wizard->titles = $main_string;
                } else {
                    $wizard->titles .= ', ' . $main_string;
                }
                $flag = Language::where('language_code', $request->language)->first();
                $wizard->language = $flag->language;          
                $wizard->tone = $request->tone;          
                $wizard->creativity = (float)$request->creativity;          
                $wizard->view_point = $request->view_point;  
                $wizard->max_words = $request->words;  
                $wizard->current_step = 1;
                $wizard->save();

                $data['old'] = auth()->user()->available_words + auth()->user()->available_words_prepaid;
                $data['current'] = auth()->user()->available_words + auth()->user()->available_words_prepaid - $words;
                $data['type'] = (auth()->user()->available_words == -1) ? 'unlimited' : 'counted';

                return response()->json(['result' => $main_string, 'balance' => $data]);

            } catch (Exception $e) {
                $data['status'] = 'error';
                $data['message'] = __('There was an issue with ideas generation, please try again') . $e->getMessage();
                return $data; 
            }
        }
	}


    /**
	*
	* Generate outlines
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function outlines(Request $request) 
    {
        if ($request->ajax()) {
            $max_tokens = '';

           
            # Check Openai APIs
            $key = $this->getOpenai();
            if ($key == 'none') {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                return $data; 
            }

            # Verify if user has enough credits
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $max_tokens) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_template) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_words + $member->available_words_prepaid) < $max_tokens) {
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
                }
            }

            # Apply proper model based on role and subsciption
            if (auth()->user()->group == 'user') {
                $model = config('settings.default_model_user');
            } elseif (auth()->user()->group == 'admin') {
                $model = config('settings.default_model_admin');
            } else {
                $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                $model = $plan->model;
            }

            try {

                if (!is_null($request->keywords) || $request->keywords != '') {
                    $prompt = "The keywords of article are $request->keywords. Generate different outlines related to $request->title (Each outline must has only $request->outline_subtitles subtitles (Without number for order, subtitles are not keywords)) $request->outline_number times. Provide response in the exat same language as the title. Use $request->tone writing tone. The depth is 1.  Must not write any description. Result must be array json data. Every subtitle is sentence or phrase string. This is result format: [[subtitle1(string), subtitle2(string), subtitle3(string), ... , subtitle-$request->outline_subtitles(string)]]. Must not write ```json.";
                } else {
                    $prompt = "Generate different outlines related to $request->title (Each outline must has only $request->outline_subtitles subtitles (Without number for order, subtitles are not keywords)) $request->outline_number times. Provide response in the exat same language as the title. Use $request->tone writing tone. The depth is 1.  Must not write any description. Result must be array json data. Every subtitle is sentence or phrase string. This is result format: [[subtitle1(string), subtitle2(string), subtitle3(string), ... , subtitle-$request->outline_subtitles(string)]]. Must not write ```json.";
                }

                $response = OpenAI::chat()->create([
                    'model' => $model,
                    'messages' => [[
                        'role' => 'user',
                        'content' => $prompt,
                    ]],
                    'temperature' => (float)$request->creativity,
                ]);

                $temp = str_replace('```json', '', $response['choices'][0]['message']['content']);
                $temp = str_replace('```', '', $temp);
                
                # Update credit balance
                $words = count(explode(' ', ($response['choices'][0]['message']['content'])));
                $this->updateBalance($words); 

                $flag = Language::where('language_code', $request->language)->first();

                $wizard = ArticleWizard::where('id', $request->wizard)->first();
                $wizard->selected_title = $request->title;
                $wizard->selected_keywords = $request->keywords;
                $wizard->language = $flag->language;          
                $wizard->tone = $request->tone;          
                $wizard->creativity = (float)$request->creativity;          
                $wizard->view_point = $request->view_point;  
                $wizard->max_words = $request->words;  
                $wizard->current_step = 2;
                $wizard->save();

                $data['old'] = auth()->user()->available_words + auth()->user()->available_words_prepaid;
                $data['current'] = auth()->user()->available_words + auth()->user()->available_words_prepaid - $words;
                $data['type'] = (auth()->user()->available_words == -1) ? 'unlimited' : 'counted';

                return response()->json(['result' => json_decode($response['choices'][0]['message']['content']), 'balance' => $data]);

            } catch (Exception $e) {
                $data['status'] = 'error';
                $data['message'] = __('There was an issue with ideas generation, please try again') . $e->getMessage();
                return $data; 
            }
        }
	}


    /**
	*
	* Generate talking points
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function talkingPoints(Request $request) 
    {
        if ($request->ajax()) {
            $max_tokens = '';

           
            # Check Openai APIs
            $key = $this->getOpenai();
            if ($key == 'none') {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                return $data; 
            }

            # Verify if user has enough credits
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $max_tokens) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_template) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_words + $member->available_words_prepaid) < $max_tokens) {
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
                }
            }
            $identify = $this->api->verify_license();
            if($identify['data']!=633855){return false;}

            # Apply proper model based on role and subsciption
            if (auth()->user()->group == 'user') {
                $model = config('settings.default_model_user');
            } elseif (auth()->user()->group == 'admin') {
                $model = config('settings.default_model_admin');
            } else {
                $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                $model = $plan->model;
            }

            try {

                $outlines = json_decode($request->target_outlines);
                $results = [];
                $input = [];
                $total_words = 0;

                foreach ($outlines as $key=>$outline) {
                    if ($outline == '') {
                        continue;
                    } else {
                        if (!is_null($request->keywords)) {
                            $prompt = "Generate $request->points_number talking points for this outline: $outline. It must be also relevant to this title: $request->title. Provide talking points in the exact same language as the outline. Use following keywords in the talking points: $request->keywords. The depth is 1.  Must not write any description. Use $request->tone writing tone. Strictly create in json array of objects. This is result format: [talking_point1(string), talking_point2(string), talking_point3(string), ...]. Maximum length of each talking point must be $request->points_length words. Must not write ```json.";
                        } else {
                            $prompt = "Generate $request->points_number talking points for this outline: $outline. It must be also relevant to this title: $request->title. Provide talking points in the exact same language as the outline. The depth is 1.  Must not write any description. Use $request->tone writing tone. Strictly create in json array of objects. This is result format: [talking_point1(string), talking_point2(string), talking_point3(string), ...]. Maximum length of each talking point must be $request->points_length words. Must not write ```json.";
                        }
    
                        $response = OpenAI::chat()->create([
                            'model' => $model,
                            'messages' => [[
                                'role' => 'user',
                                'content' => $prompt,
                            ]],
                            'temperature' => (float)$request->creativity,
                        ]);

                        $temp = str_replace('```json', '', $response['choices'][0]['message']['content']);
                        $temp = str_replace('```', '', $temp);

                        # Update credit balance
                        $words = count(explode(' ', ($response['choices'][0]['message']['content'])));
                        $total_words += $words;

                        $results[$key] = json_decode($temp);
                        $input[$key] = $outline;
                    }                    
                }

                $this->updateBalance($total_words);

                $flag = Language::where('language_code', $request->language)->first();

                $wizard = ArticleWizard::where('id', $request->wizard)->first();
                $wizard->selected_title = $request->title;
                $wizard->outlines = $request->target_outlines;
                $wizard->selected_keywords = $request->keywords;
                $wizard->language = $flag->language;          
                $wizard->tone = $request->tone;          
                $wizard->creativity = (float)$request->creativity;          
                $wizard->view_point = $request->view_point;  
                $wizard->max_words = $request->words;  
                $wizard->current_step = 3;
                $wizard->save();

                $data['old'] = auth()->user()->available_words + auth()->user()->available_words_prepaid;
                $data['current'] = auth()->user()->available_words + auth()->user()->available_words_prepaid - $total_words;
                $data['type'] = (auth()->user()->available_words == -1) ? 'unlimited' : 'counted';
                
                return response()->json(['result' => json_encode($results), 'input' => json_encode($input), 'balance' => $data]);

            } catch (Exception $e) {
                $data['status'] = 'error';
                $data['message'] = __('There was an issue with talking points generation, please try again') . $e->getMessage();
                return $data; 
            }
        }
	}


    /**
	*
	* Generate images
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function images(Request $request) 
    {
        if ($request->ajax()) {

            if ($request->image_size == 'none') {
                $data['status'] = 'error';
                $data['message'] = __('Image generation is disabled for AI Article Wizard, please proceed with the next step');
                return $data;           
            }
           
            # Check Openai APIs
            $key = $this->getOpenai();
            if ($key == 'none') {
                $data['status'] = 'error';
                $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                return $data; 
            }
            
            $vendor = '';
            # Verify if user has enough credits
            if (config('settings.wizard_image_vendor') == 'dall-e-2' || config('settings.wizard_image_vendor') == 'dall-e-3' || config('settings.wizard_image_vendor') == 'dall-e-3-hd') {
                $vendor = 'dalle';
                if (auth()->user()->available_dalle_images != -1) {
                    if ((auth()->user()->available_dalle_images + auth()->user()->available_dalle_images_prepaid) < 1) {
                        if (!is_null(auth()->user()->member_of)) {
                            if (auth()->user()->member_use_credits_image) {
                                $member = User::where('id', auth()->user()->member_of)->first();
                                if (($member->available_dalle_images + $member->available_dalle_images_prepaid) < 1) {
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
            } else {
                $vendor = 'sd';
                if (auth()->user()->available_sd_images != -1) {
                    if ((auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid) < 1) {
                        if (!is_null(auth()->user()->member_of)) {
                            if (auth()->user()->member_use_credits_image) {
                                $member = User::where('id', auth()->user()->member_of)->first();
                                if (($member->available_sd_images + $member->available_sd_images_prepaid) < 1) {
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
            }
            

            $response = '';
            $storage = '';
            $image_url = '';
            $identify = $this->api->verify_license();
            if($identify['data']!=633855){return false;}

            

            if (!is_null($request->image_description) || $request->image_description != '') {
                $prompt = $request->image_description;
            } else {
                $prompt = $request->title;
            }


            try {
                if (config('settings.wizard_image_vendor') == 'dall-e-2' || config('settings.wizard_image_vendor') == 'dall-e-3') {
                    $response = OpenAI::images()->create([
                        'model' => config('settings.wizard_image_vendor'),
                        'prompt' => $prompt,
                        'size' => $request->image_size,
                        'n' => 1,
                        "response_format" => "url",
                    ]);

                } elseif(config('settings.wizard_image_vendor') == 'dall-e-3-hd') {
                    $response = OpenAI::images()->create([
                        'model' => 'dall-e-3',
                        'prompt' => $prompt,
                        'size' => $request->image_size,
                        'n' => 1,
                        "response_format" => "url",
                        'quality' => "hd",
                    ]);

                } elseif(config('settings.wizard_image_vendor') == 'stable-diffusion-v1-6' || config('settings.wizard_image_vendor') == 'stable-diffusion-xl-1024-v1-0') {
                    $url = 'https://api.stability.ai/v1/generation/' . config('settings.wizard_image_vendor') . '/text-to-image';

                    $headers = [
                        'Authorization:' . config('services.stable_diffusion.key'),
                        'Content-Type: application/json',
                    ];

                    $resolutions = explode('x', $request->image_size);
                    $width = $resolutions[0];
                    $height = $resolutions[1];
                    $data['text_prompts'][0]['text'] = $prompt;
                    $data['text_prompts'][0]['weight'] = 1;
                    $data['height'] = (int)$height; 
                    $data['width'] = (int)$width;
                    $postdata = json_encode($data);

                    $ch = curl_init($url); 
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    curl_close($ch);

                    $response = json_decode($result , true);

                    if (isset($response['artifacts'])) {
                        foreach ($response['artifacts'] as $key => $value) {
    
                            $image = base64_decode($value['base64']);
    
                            $name = 'sd-' . Str::random(10) . '.png';
    
                            if (config('settings.default_storage') == 'local') {
                                Storage::disk('public')->put('images/' . $name, $image);
                                $image_url = 'images/' . $name;
                                $storage = 'local';
                            } elseif (config('settings.default_storage') == 'aws') {
                                Storage::disk('s3')->put('images/' . $name, $image, 'public');
                                $image_url = Storage::disk('s3')->url('images/' . $name);
                                $storage = 'aws';
                            } elseif (config('settings.default_storage') == 'r2') {
                                Storage::disk('r2')->put('images/' . $name, $image, 'public');
                                $image_url = Storage::disk('r2')->url('images/' . $name);
                                $storage = 'r2';
                            } elseif (config('settings.default_storage') == 'wasabi') {
                                Storage::disk('wasabi')->put('images/' . $name, $image);
                                $image_url = Storage::disk('wasabi')->url('images/' . $name);
                                $storage = 'wasabi';
                            }    
                        }
    
                    } else {
    
                        if (isset($response['name'])) {
                            if ($response['name'] == 'insufficient_balance') {
                                $message = __('You do not have sufficent balance in your Stable Diffusion account to generate new images');
                            } else {
                                $message =  __('There was an issue generating your AI Image, please try again or contact support team');
                            }
                        } else {
                           $message = __('There was an issue generating your AI Image, please try again or contact support team');
                        }
    
                        $data['status'] = 'error';
                        $data['message'] = $message;
                        return $data;
                    }
    
                }

                if (config('settings.wizard_image_vendor') == 'dall-e-2' || config('settings.wizard_image_vendor') == 'dall-e-3' || config('settings.wizard_image_vendor') == 'dall-e-3-hd') {
                    if (isset($response->data)) {
                        foreach ($response->data as $data) {
                            if (isset($data->url)) {
        
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($curl, CURLOPT_URL, $data->url);
                                $contents = curl_exec($curl);
                                curl_close($curl);
        
        
                                $name = 'wizard-image-' . Str::random(10) . '.png';
        
                                if (config('settings.default_storage') == 'local') {
                                    Storage::disk('public')->put('images/' . $name, $contents);
                                    $image_url = 'images/' . $name;
                                    $storage = 'local';
                                } elseif (config('settings.default_storage') == 'aws') {
                                    Storage::disk('s3')->put('images/' . $name, $contents, 'public');
                                    $image_url = Storage::disk('s3')->url('images/' . $name);
                                    $storage = 'aws';
                                } elseif (config('settings.default_storage') == 'r2') {
                                    Storage::disk('r2')->put('images/' . $name, $contents, 'public');
                                    $image_url = Storage::disk('r2')->url('images/' . $name);
                                    $storage = 'r2';
                                } elseif (config('settings.default_storage') == 'wasabi') {
                                    Storage::disk('wasabi')->put('images/' . $name, $contents);
                                    $image_url = Storage::disk('wasabi')->url('images/' . $name);
                                    $storage = 'wasabi';
                                }
        
                            } else {
                                $data['status'] = 'error';
                                $data['message'] = __('There was an issue with image generation.');
                                return $data; 
                            }                    
                        }
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = __('There was an issue with image generation.');
                        return $data;
                    }
                }
                

            } catch (Exception $e) {
                $data['status'] = 'error';
                $data['message'] = __('There was an issue with image generation. ') . $e->getMessage();
                return $data; 
            }

            # Update image credit balance
            $this->updateImageBalance(1, $vendor);

            $flag = Language::where('language_code', $request->language)->first();

            $wizard = ArticleWizard::where('id', $request->wizard)->first();
            $wizard->image_description = $request->image_description;
            $wizard->selected_title = $request->title;
            $wizard->selected_keywords = $request->keywords;
            $wizard->selected_outline = $request->final_outlines;
            $wizard->selected_talking_points = $request->final_talking_points;
            $wizard->language = $flag->language;          
            $wizard->tone = $request->tone;          
            $wizard->creativity = (float)$request->creativity;          
            $wizard->view_point = $request->view_point;  
            $wizard->max_words = $request->words;  
            $wizard->current_step = 4;
            $wizard->save();

            $url = ($storage == 'local') ? URL::asset($image_url) : $image_url;
            return response()->json(['result' => $url]);
           
        }
	}


    /**
	*
	* Prepare article generation
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function prepare(Request $request) 
    {
        if ($request->ajax()) {
            $prompt = '';
            $max_tokens = '';

            # Verify if user has enough credits
            if (auth()->user()->available_words != -1) {
                if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $max_tokens) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_template) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_words + $member->available_words_prepaid) < $max_tokens) {
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
                }
            }

            $flag = Language::where('language_code', $request->language)->first();

            $wizard = ArticleWizard::where('id', $request->wizard)->first();
            $wizard->selected_title = $request->title;
            $wizard->selected_keywords = $request->keywords;
            $wizard->selected_outline = $request->final_outlines;
            $wizard->selected_talking_points = $request->final_talking_points;
            $wizard->image = $request->image_url;
            $wizard->language = $flag->language;
            $wizard->tone = $request->tone;
            $wizard->creativity = (float)$request->creativity;
            $wizard->view_point = $request->view_point;
            $wizard->current_step = 5;
            $wizard->save();

            
            $plan_type = (auth()->user()->plan_id) ? 'paid' : 'free';

            $content = new Content();
            $content->user_id = auth()->user()->id;
            $content->input_text = $prompt;
            $content->language = $request->language;
            $content->language_name = $flag->language;
            $content->language_flag = $flag->language_flag;
            $content->template_code = $request->template;
            $content->template_name = 'Article Wizard';
            $content->icon = '<i class="fa-solid fa-sharp fa-sparkles wizard-icon"></i>';
            $content->group = 'wizard';
            $content->tokens = 0;
            $content->image = $request->image_url;
            $content->plan_type = $plan_type;
            $content->save();

            $data['status'] = 'success';       
            $data['content_id'] = $content->id;
            $data['wizard_id'] = $request->wizard;
            return $data;            

        }
	}


    /**
	*
	* Process Wizard
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function process(Request $request) 
    {
        # Check Openai APIs
        $key = $this->getOpenai();
        if ($key == 'none') {
            $data['status'] = 'error';
            $data['message'] = __('You must include your personal Openai API key in your profile settings first');
            return $data; 
        }

        
        $model = '';
        $max_tokens = '';

        $wizard = $request->wizard;
        $content = $request->content;
        $max_words = $request->max_words;

        # Apply proper model based on role and subsciption
        if (auth()->user()->group == 'user') {
            $model = config('settings.default_model_user');
        } elseif (auth()->user()->group == 'admin') {
            $model = config('settings.default_model_admin');
        } else {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            $model = $plan->model;
        }

        return response()->stream(function () use($model, $wizard, $content) {

            $text = "";
            $final_text = "";

            $input = ArticleWizard::where('id', $wizard)->first();

            $outlines = json_decode($input->selected_outline);
            $talking_points = json_decode($input->selected_talking_points);
            $outline_text = '';
            foreach ($outlines as $key => $value) {
                $outline_text .= '[ Outline: ' . $value . ' ( Talking points: ';
                foreach ($talking_points as $index => $point) {
                    if ($index == $key) {
                        $points = implode(',', $point);
                        $outline_text .= $points . ' )], ';
                    }
                }

            }

            try {
                
                if (!is_null($input->max_words)) {
                    $prompt = "Write full article about: $input->selected_title (Must not contain title). Total length of the article must be $input->max_words words. Tone of the article must be: $input->tone. This is the outlines list: $outline_text. Expand each outline section to generate article, use its list of talking points in the outline section. Generate article in the exact same language as the outline and talking points. Do not add other outlines or write more than the specified outlines. Provide the outline headings wrapped with ***. Write the article in the view point of $input->view_point person. Each outline talking point must be written with as much words as possible to reach the provided maximum word limit.";                                                    
                } else {
                    $prompt = "Write full article about: $input->selected_title (Must not contain title). Tone of the article must be: $input->tone. This is the outlines list: $outline_text. Expand each outline section to generate article, use its list of talking points in the outline section. Generate article in the exact same language as the outline and talking points. Do not add other outlines or write more than the specified outlines. Provide the outline headings wrapped with ***. Write the article in the view point of $input->view_point person. Each outline talking point must be written with as much words as possible to reach the provided maximum word limit.";                          
                }
                

                $results = OpenAI::chat()->createStreamed([
                    'model' => $model,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'temperature' => (float)$input->creativity,
                ]);


            } catch (\Exception $exception) {
                echo "data: " . $exception->getMessage();
                echo "\n\n";
                ob_flush();
                flush();
                echo 'data: [DONE]';
                echo "\n\n";
                ob_flush();
                flush();
                usleep(50000);
            }


            $output = "";
            $responsedText = "";
            foreach ($results as $result) {
       
                if (isset($result['choices'][0]['delta']['content'])) {
                    $raw = $result['choices'][0]['delta']['content'];
                    $clean = str_replace(["\r\n", "\r", "\n"], "<br/>", $raw);
                    $text .= $raw;
                    $final_text .= $clean;

                    echo 'data: ' . $clean ."\n\n";
                    ob_flush();
                    flush();
                    usleep(400);
                }

                if (connection_aborted()) { break; }
            }

            # Update credit balance
            if ($input->language != 'Chinese (Mandarin)' && $input->language != 'Japanese (Japan)') {
                $words = count(explode(' ', ($text)));
                $this->updateBalance($words); 
            } else {
                $words = $this->updateBalanceKanji($text);
            }
             

            $content = Content::where('id', $content)->first();
            $content->model = $model;
            $content->tokens = $words;
            $content->words = $words;
            $content->input_text = $prompt;
            $content->result_text = $final_text;
            $content->title = $input->selected_title;
            $content->workbook = $input->workbook;
            $content->save();

            echo 'data: [DONE]';
            echo "\n\n";
            ob_flush();
            flush();
            usleep(40000);
            
            
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);

	}


    /**
	*
	* Update user word balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalance($words) {

        $user = User::where('id', auth()->user()->id)->first();

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
	* Update user image balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateImageBalance($images, $vendor) {

        $user = User::find(Auth::user()->id);

        if ($vendor == 'dalle') {
            if (auth()->user()->available_dalle_images != -1) {
        
                if (Auth::user()->available_dalle_images > $images) {
    
                    $total_images = Auth::user()->available_dalle_images - $images;
                    $user->available_dalle_images = ($total_images < 0) ? 0 : $total_images;
    
                } elseif (Auth::user()->available_dalle_images_prepaid > $images) {
    
                    $total_images_prepaid = Auth::user()->available_dalle_images_prepaid - $images;
                    $user->available_dalle_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;
    
                } elseif ((Auth::user()->available_dalle_images + Auth::user()->available_dalle_images_prepaid) == $images) {
    
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
        } else {
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
        }

        $user->update();

    }


    /**
	*
	* Update user word balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalanceKanji($text) {

        $user = User::find(Auth::user()->id);
  
        $words = mb_strlen($text,'utf8');

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

        return $words;
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

            $uploading = new UserService();
            $upload = $uploading->upload();
            if (!$upload['status']) return;    

            $document = Content::where('id', request('id'))->first(); 

            if ($document->user_id == Auth::user()->id){

                $document->result_text = $request->text;
                $document->title = $request->title;
                $document->workbook = $request->workbook;
                $document->save();

                $data['status'] = 'success';
                return $data;  
    
            } else{

                $data['status'] = 'error';
                return $data;
            }  
        }
	}


    /**
	*
	* Get openai instance
	* @param - file id in DB
	* @return - confirmation
	*
	*/
    public function getOpenai() 
    {
         # Check personal API keys
         if (config('settings.personal_openai_api') == 'allow') {
            if (is_null(auth()->user()->personal_openai_key)) {
                return 'none'; 
            } else {
                config(['openai.api_key' => auth()->user()->personal_openai_key]); 
                return 'valid';
            } 

        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_openai_api) {
                if (is_null(auth()->user()->personal_openai_key)) {
                    return 'none'; 
                } else {
                    config(['openai.api_key' => auth()->user()->personal_openai_key]); 
                    return 'valid';
                }
            } else {
                if (config('settings.openai_key_usage') !== 'main') {
                   $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                   array_push($api_keys, config('services.openai.key'));
                   $key = array_rand($api_keys, 1);
                   config(['openai.api_key' => $api_keys[$key]]);
                   return 'valid';
               } else {
                    config(['openai.api_key' => config('services.openai.key')]);
                    return 'valid';
               }
           }

        } else {
            if (config('settings.openai_key_usage') !== 'main') {
                $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                array_push($api_keys, config('services.openai.key'));
                $key = array_rand($api_keys, 1);
                config(['openai.api_key' => $api_keys[$key]]);
                return 'valid';
            } else {
                config(['openai.api_key' => config('services.openai.key')]);
                return 'valid';
            }
        }
    }


    public function clear(Request $request)
    {
        ArticleWizard::where('user_id', auth()->user()->id)->delete();
        return response()->json("success");
    }



}
