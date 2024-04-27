<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\Subscriber;
use App\Models\FineTuneModel;
use DataTables;
use Exception;
use DB;

class FinanceSubscriptionPlanController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubscriptionPlan::all()->sortByDesc("created_at");          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a href="'. route("admin.finance.plan.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons edit-action-button" title="'. __('View Plan') .'"></i></a>
                                            <a href="'. route("admin.finance.plan.edit", $row["id"] ). '"><i class="fa-solid fa-file-pen table-action-buttons view-action-button" title="'. __('Update Plan') .'"></i></a>
                                            <a class="deletePlanButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="'. __('Delete Plan') .'"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd/m/Y').'</span><br><span>'.date_format($row["created_at"], 'H:i A').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_priority = '<span class="cell-box plan-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_priority;
                    })
                    ->addColumn('frequency', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["payment_frequency"]).'">'.ucfirst($row["payment_frequency"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-credits', function($row){
                        $words = ($row["words"] == -1) ? 'Unlimited' : number_format($row["words"]);
                        $dalle_images = ($row["dalle_images"] == -1) ? 'Unlimited' : number_format($row["dalle_images"]);
                        $sd_images = ($row["sd_images"] == -1) ? 'Unlimited' : number_format($row["sd_images"]);
                        $characters = ($row["characters"] == -1) ? 'Unlimited' : number_format($row["characters"]);
                        $minutes = ($row["minutes"] == -1) ? 'Unlimited' : number_format($row["minutes"]);
                        $custom_credits = '<span>'. $words .' / '. $dalle_images . ' / '. $sd_images . ' / '. $characters . ' / ' . $minutes .'</span>';
                        return $custom_credits;
                    })
                    ->addColumn('custom-subscribers', function($row){
                        $value = $this->countSubscribers($row['id']);
                        $custom_storage = '<span>'.$value.'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-name', function($row){
                        $custom_name = '<span class="font-weight-bold">'.$row["plan_name"].'</span><br><span>'.$row["price"] . ' ' . $row["currency"].'</span>';
                        return $custom_name;
                    })
                    ->addColumn('custom-featured', function($row){
                        $icon = ($row['featured'] == true) ? '<i class="fa-solid fa-circle-check text-success fs-16"></i>' : '<i class="fa-solid fa-circle-xmark fs-16"></i>';
                        $custom_featured = '<span class="font-weight-bold">'.$icon.'</span>';
                        return $custom_featured;
                    })
                    ->addColumn('custom-free', function($row){
                        $icon = ($row['free'] == true) ? '<i class="fa-solid fa-circle-check text-success fs-16"></i>' : '<i class="fa-solid fa-circle-xmark fs-16"></i>';
                        $custom_featured = '<span class="font-weight-bold">'.$icon.'</span>';
                        return $custom_featured;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-subscribers', 'frequency', 'custom-name', 'custom-featured', 'custom-free', 'custom-credits'])
                    ->make(true);
                    
        }

        return view('admin.finance.plans.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $models = FineTuneModel::all();

        return view('admin.finance.plans.create', compact('models'));
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
            'plan-status' => 'required',
            'plan-name' => 'required',
            'cost' => 'required|numeric',
            'currency' => 'required',
            'words' => 'required',
            'characters' => 'required',
            'minutes' => 'required',
            'frequency' => 'required',
        ]);

        if (request('voiceover-feature') == 'on') {
            $voiceover = true; 
        } else {
            $voiceover = false;
        }

        if (request('image-feature') == 'on') {
            $image = true; 
        } else {
            $image = false;
        }

        if (request('whisper-feature') == 'on') {
            $whisper = true; 
        } else {
            $whisper = false;
        }

        if (request('chat-feature') == 'on') {
            $chat = true; 
        } else {
            $chat = false;
        }

        if (request('code-feature') == 'on') {
            $code = true; 
        } else {
            $code = false;
        }

        if (request('personal-openai-api') == 'on') {
            $openai_personal = true; 
        } else {
            $openai_personal = false;
        }

        if (request('personal-sd-api') == 'on') {
            $sd_personal = true; 
        } else {
            $sd_personal = false;
        }

        if (request('wizard-feature') == 'on') {
            $wizard = true; 
        } else {
            $wizard = false;
        }

        if (request('vision-feature') == 'on') {
            $vision = true; 
        } else {
            $vision = false;
        }

        if (request('chat-image-feature') == 'on') {
            $chat_image = true; 
        } else {
            $chat_image = false;
        }

        if (request('file-chat-feature') == 'on') {
            $file = true; 
        } else {
            $file = false;
        }

        if (request('internet-feature') == 'on') {
            $internet = true; 
        } else {
            $internet = false;
        }

        if (request('chat-web-feature') == 'on') {
            $web = true; 
        } else {
            $web = false;
        }

        if (request('smart-editor-feature') == 'on') {
            $smart = true; 
        } else {
            $smart = false;
        }

        if (request('rewriter-feature') == 'on') {
            $rewriter = true; 
        } else {
            $rewriter = false;
        }

        if (request('video-image-feature') == 'on') {
            $video_image = true; 
        } else {
            $video_image = false;
        }

        if (request('voice-clone-feature') == 'on') {
            $clone = true; 
        } else {
            $clone = false;
        }

        if (request('sound-studio-feature') == 'on') {
            $studio = true; 
        } else {
            $studio = false;
        }

        if (request('plagiarism-feature') == 'on') {
            $plagiarism = true; 
        } else {
            $plagiarism = false;
        }

        if (request('detector-feature') == 'on') {
            $detector = true; 
        } else {
            $detector = false;
        }

        if (request('personal-chat-feature') == 'on') {
            $personal_chat = true; 
        } else {
            $personal_chat = false;
        }

        if (request('personal-template-feature') == 'on') {
            $personal_template = true; 
        } else {
            $personal_template = false;
        }

        if (request('brand-voice-feature') == 'on') {
            $brand_voice = true; 
        } else {
            $brand_voice = false;
        }

        $voiceover_vendors = '';
        if (!is_null(request('voiceover_vendors'))) {
            foreach (request('voiceover_vendors') as $key => $value) {
                if ($key === array_key_last(request('voiceover_vendors'))) {
                    $voiceover_vendors .= $value; 
                } else {
                    $voiceover_vendors .= $value . ', '; 
                }
                
            }
        }

        try {
            $plan = new SubscriptionPlan([
                'paypal_gateway_plan_id' => request('paypal_gateway_plan_id'),
                'stripe_gateway_plan_id' => request('stripe_gateway_plan_id'),
                'paystack_gateway_plan_id' => request('paystack_gateway_plan_id'),
                'razorpay_gateway_plan_id' => request('razorpay_gateway_plan_id'),
                'flutterwave_gateway_plan_id' => request('flutterwave_gateway_plan_id'),
                'paddle_gateway_plan_id' => request('paddle_gateway_plan_id'),
                'status' => request('plan-status'),
                'plan_name' => request('plan-name'),
                'price' => request('cost'),
                'currency' => request('currency'),
                'free' => request('free-plan'),
                'image_feature' => $image,
                'voiceover_feature' => $voiceover,
                'transcribe_feature' => $whisper,
                'chat_feature' => $chat,
                'code_feature' => $code,
                'templates' => request('templates'),
                'words' => request('words'),
                'chats' => request('chats'),
                'characters' => request('characters'),
                'minutes' => request('minutes'),
                'payment_frequency' => request('frequency'),
                'primary_heading' => request('primary-heading'),
                'featured' => request('featured'),
                'plan_features' => request('features'),
                'max_tokens' => request('tokens'),
                'model' => request('model'),
                'model_chat' => request('chat-model'),
                'team_members' => request('team-members'),
                'personal_openai_api' => $openai_personal,
                'personal_sd_api' => $sd_personal,
                'days' => request('days'),
                'dalle_image_engine' => request('dalle-image-engine'),
                'sd_image_engine' => request('sd-image-engine'),
                'wizard_feature' => $wizard,
                'vision_feature' => $vision,
                'internet_feature' => $internet,
                'chat_image_feature' => $chat_image,
                'file_chat_feature' => $file,
                'chat_web_feature' => $web,
                'chat_csv_file_size' => request('chat-csv-file-size'),
                'chat_pdf_file_size' => request('chat-pdf-file-size'),
                'chat_word_file_size' => request('chat-word-file-size'),
                'voice_clone_number' => request('voice_clone_number'),
                'rewriter_feature' => $rewriter,
                'smart_editor_feature' => $smart,
                'video_image_feature' => $video_image,
                'voice_clone_feature' => $clone,
                'sound_studio_feature' => $studio,
                'plagiarism_feature' => $plagiarism,
                'ai_detector_feature' => $detector,
                'plagiarism_pages' => request('plagiarism-pages'),
                'ai_detector_pages' => request('detector-pages'),
                'personal_chats_feature' => $personal_chat,
                'personal_templates_feature' => $personal_template,
                'voiceover_vendors' => $voiceover_vendors,
                'brand_voice_feature' => $brand_voice,
                'file_result_duration' => request('file-result-duration'),
                'document_result_duration' => request('document-result-duration'),
                'dalle_images' => request('dalle-images'),
                'sd_images' => request('sd-images'),
            ]); 
                   
            $plan->save();            
    
            toastr()->success(__('New subscription plan has been created successfully'));
            return redirect()->route('admin.finance.plans');

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionPlan $id)
    {
        return view('admin.finance.plans.show', compact('id'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriptionPlan $id)
    {
        $models = FineTuneModel::all();
        $vendors = explode(',', $id->voiceover_vendors);

        return view('admin.finance.plans.edit', compact('id', 'models', 'vendors'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriptionPlan $id)
    {
        request()->validate([
            'plan-status' => 'required',
            'plan-name' => 'required',
            'cost' => 'required|numeric',
            'currency' => 'required',
            'words' => 'required',
            'characters' => 'required',
            'minutes' => 'required',
            'frequency' => 'required',
        ]);

        if (request('voiceover-feature') == 'on') {
            $voiceover = true; 
        } else {
            $voiceover = false;
        }

        if (request('image-feature') == 'on') {
            $image = true; 
        } else {
            $image = false;
        }

        if (request('whisper-feature') == 'on') {
            $whisper = true; 
        } else {
            $whisper = false;
        }

        if (request('chat-feature') == 'on') {
            $chat = true; 
        } else {
            $chat = false;
        }

        if (request('code-feature') == 'on') {
            $code = true; 
        } else {
            $code = false;
        }

        if (request('personal-openai-api') == 'on') {
            $openai_personal = true; 
        } else {
            $openai_personal = false;
        }

        if (request('personal-sd-api') == 'on') {
            $sd_personal = true; 
        } else {
            $sd_personal = false;
        }

        if (request('wizard-feature') == 'on') {
            $wizard = true; 
        } else {
            $wizard = false;
        }

        if (request('vision-feature') == 'on') {
            $vision = true; 
        } else {
            $vision = false;
        }

        if (request('chat-image-feature') == 'on') {
            $chat_image = true; 
        } else {
            $chat_image = false;
        }

        if (request('file-chat-feature') == 'on') {
            $file = true; 
        } else {
            $file = false;
        }

        if (request('internet-feature') == 'on') {
            $internet = true; 
        } else {
            $internet = false;
        }

        if (request('chat-web-feature') == 'on') {
            $web = true; 
        } else {
            $web = false;
        }

        if (request('smart-editor-feature') == 'on') {
            $smart = true; 
        } else {
            $smart = false;
        }

        if (request('rewriter-feature') == 'on') {
            $rewriter = true; 
        } else {
            $rewriter = false;
        }

        if (request('video-image-feature') == 'on') {
            $video_image = true; 
        } else {
            $video_image = false;
        }

        if (request('voice-clone-feature') == 'on') {
            $clone = true; 
        } else {
            $clone = false;
        }

        if (request('sound-studio-feature') == 'on') {
            $studio = true; 
        } else {
            $studio = false;
        }

        if (request('plagiarism-feature') == 'on') {
            $plagiarism = true; 
        } else {
            $plagiarism = false;
        }

        if (request('detector-feature') == 'on') {
            $detector = true; 
        } else {
            $detector = false;
        }

        if (request('personal-chat-feature') == 'on') {
            $personal_chat = true; 
        } else {
            $personal_chat = false;
        }

        if (request('personal-template-feature') == 'on') {
            $personal_template = true; 
        } else {
            $personal_template = false;
        }

        if (request('brand-voice-feature') == 'on') {
            $brand_voice = true; 
        } else {
            $brand_voice = false;
        }

        $voiceover_vendors = '';
        if (!is_null(request('voiceover_vendors'))) {
            foreach (request('voiceover_vendors') as $key => $value) {
                if ($key === array_key_last(request('voiceover_vendors'))) {
                    $voiceover_vendors .= $value; 
                } else {
                    $voiceover_vendors .= $value . ', '; 
                }                
            }
        }

        try {

            $id->update([
                'paypal_gateway_plan_id' => request('paypal_gateway_plan_id'),
                'stripe_gateway_plan_id' => request('stripe_gateway_plan_id'),
                'paystack_gateway_plan_id' => request('paystack_gateway_plan_id'),
                'razorpay_gateway_plan_id' => request('razorpay_gateway_plan_id'),
                'flutterwave_gateway_plan_id' => request('flutterwave_gateway_plan_id'),
                'paddle_gateway_plan_id' => request('paddle_gateway_plan_id'),
                'status' => request('plan-status'),
                'plan_name' => request('plan-name'),
                'price' => request('cost'),
                'currency' => request('currency'),
                'free' => request('free-plan'),
                'words' => request('words'),
                'characters' => request('characters'),
                'minutes' => request('minutes'),
                'payment_frequency' => request('frequency'),
                'primary_heading' => request('primary-heading'),
                'featured' => request('featured'),
                'plan_features' => request('features'),
                'image_feature' => $image,
                'voiceover_feature' => $voiceover,
                'transcribe_feature' => $whisper,
                'chat_feature' => $chat,
                'code_feature' => $code,
                'templates' => request('templates'),
                'chats' => request('chats'),
                'max_tokens' => request('tokens'),
                'model' => request('model'),
                'model_chat' => request('chat-model'),
                'team_members' => request('team-members'),
                'personal_openai_api' => $openai_personal,
                'personal_sd_api' => $sd_personal,
                'days' => request('days'),
                'dalle_image_engine' => request('dalle-image-engine'),
                'sd_image_engine' => request('sd-image-engine'),
                'wizard_feature' => $wizard,
                'vision_feature' => $vision,
                'internet_feature' => $internet,
                'chat_image_feature' => $chat_image,
                'file_chat_feature' => $file,
                'chat_web_feature' => $web,
                'chat_csv_file_size' => request('chat-csv-file-size'),
                'chat_pdf_file_size' => request('chat-pdf-file-size'),
                'chat_word_file_size' => request('chat-word-file-size'),
                'voice_clone_number' => request('voice_clone_number'),
                'rewriter_feature' => $rewriter,
                'smart_editor_feature' => $smart,
                'video_image_feature' => $video_image,
                'voice_clone_feature' => $clone,
                'sound_studio_feature' => $studio,
                'plagiarism_feature' => $plagiarism,
                'ai_detector_feature' => $detector,
                'plagiarism_pages' => request('plagiarism-pages'),
                'ai_detector_pages' => request('detector-pages'),
                'personal_chats_feature' => $personal_chat,
                'personal_templates_feature' => $personal_template,
                'voiceover_vendors' => $voiceover_vendors,
                'brand_voice_feature' => $brand_voice,
                'file_result_duration' => request('file-result-duration'),
                'document_result_duration' => request('document-result-duration'),
                'dalle_images' => request('dalle-images'),
                'sd_images' => request('sd-images'),
            ]); 
            
            toastr()->success(__('Selected plan has been updated successfully'));
            return redirect()->route('admin.finance.plans');
            
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $plan = SubscriptionPlan::find(request('id'));

            if($plan) {

                $plan->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        }
    }

    public function countSubscribers($id)
    {
        $total_voiceover = Subscriber::select(DB::raw("count(id) as data"))
                ->where('plan_id', $id)
                ->where('status', 'Active')
                ->get();  
        
        return $total_voiceover[0]['data'];
    }
}
