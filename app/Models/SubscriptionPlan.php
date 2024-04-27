<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paypal_gateway_plan_id',
        'stripe_gateway_plan_id',
        'paystack_gateway_plan_id',
        'razorpay_gateway_plan_id',
        'flutterwave_gateway_plan_id',
        'paddle_gateway_plan_id',
        'status',
        'plan_name',
        'price',
        'currency',
        'words',
        'images',
        'payment_frequency',
        'primary_heading', 
        'featured',
        'model',
        'model_chat',
        'plan_features', 
        'free',
        'templates',
        'image_feature',
        'max_tokens',
        'characters',
        'minutes',
        'voiceover_feature',
        'transcribe_feature',
        'code_feature',
        'chat_feature',
        'chats',
        'image_storage_days',
        'voiceover_storage_days',
        'whisper_storage_days',
        'team_members',
        'personal_openai_api',
        'personal_sd_api',
        'days',
        'dalle_image_engine',
        'sd_image_engine',
        'wizard_feature',
        'vision_feature',
        'internet_feature',
        'chat_image_feature',
        'chat_web_feature',
        'chat_csv_file_size',
        'chat_pdf_file_size',
        'rewriter_feature',
        'smart_editor_feature',
        'file_chat_feature',
        'chat_word_file_size',
        'voice_clone_number',
        'video_image_feature',
        'voice_clone_feature',
        'sound_studio_feature',
        'plagiarism_feature',
        'ai_detector_feature',
        'plagiarism_pages',
        'ai_detector_pages',
        'personal_chats_feature',
        'personal_templates_feature',
        'voiceover_vendors',
        'brand_voice_feature',
        'file_result_duration',
        'document_result_duration',
        'dalle_images',
        'sd_images',
    ];

    /**
     * Plan can have many subscribers
     * 
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscriber::class);
    }
}
