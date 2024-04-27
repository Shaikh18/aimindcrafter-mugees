<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->decimal('price', 15, 2)->unsigned();
            $table->string('currency')->default('USD');
            $table->string('status')->default('active')->comment('active|closed');
            $table->string('templates')->nullable();
            $table->string('model')->default('gpt-3.5-turbo')->nullable();
            $table->string('model_chat')->default('gpt-3.5-turbo')->nullable();
            $table->string('chats')->nullable();
            $table->integer('words')->default(0);
            $table->integer('images')->default(0);
            $table->integer('max_tokens')->default(0);
            $table->string('payment_frequency')->nullable()->comment('monthly|yearly');
            $table->string('primary_heading')->nullable();
            $table->boolean('featured')->nullable()->default(0);
            $table->boolean('free')->nullable()->default(0);
            $table->boolean('image_feature')->nullable()->default(1);
            $table->longText('plan_features')->nullable();
            $table->integer('characters')->default(0);
            $table->integer('minutes')->default(0);
            $table->integer('image_storage_days')->default(0);
            $table->integer('voiceover_storage_days')->default(0);
            $table->integer('whisper_storage_days')->default(0);
            $table->boolean('voiceover_feature')->nullable()->default(1);
            $table->boolean('transcribe_feature')->nullable()->default(1);
            $table->boolean('code_feature')->nullable()->default(1);
            $table->boolean('chat_feature')->nullable()->default(1);
            $table->string('paypal_gateway_plan_id')->nullable();
            $table->string('stripe_gateway_plan_id')->nullable();
            $table->string('paystack_gateway_plan_id')->nullable();
            $table->string('razorpay_gateway_plan_id')->nullable();
            $table->string('flutterwave_gateway_plan_id')->nullable();
            $table->string('paddle_gateway_plan_id')->nullable();
            $table->integer('team_members')->nullable()->default(0);
            $table->boolean('personal_openai_api')->default(false)->nullable();
            $table->boolean('personal_sd_api')->default(false)->nullable();
            $table->integer('days')->nullable();
            $table->string('dalle_image_engine')->nullable();
            $table->string('sd_image_engine')->nullable();
            $table->boolean('wizard_feature')->nullable()->default(1);
            $table->boolean('vision_feature')->nullable()->default(1);
            $table->boolean('internet_feature')->nullable()->default(1);
            $table->boolean('chat_image_feature')->nullable()->default(1);
            $table->boolean('chat_web_feature')->nullable()->default(1);
            $table->float('chat_csv_file_size')->nullable()->default(1);
            $table->float('chat_pdf_file_size')->nullable()->default(1);
            $table->boolean('rewriter_feature')->nullable()->default(1);
            $table->boolean('smart_editor_feature')->nullable()->default(1);
            $table->boolean('file_chat_feature')->nullable()->default(1);
            $table->boolean('video_image_feature')->nullable()->default(0);
            $table->boolean('voice_clone_feature')->nullable()->default(0);
            $table->boolean('sound_studio_feature')->nullable()->default(0);
            $table->float('chat_word_file_size')->nullable()->default(1);
            $table->integer('voice_clone_number')->nullable()->default(0);
            $table->boolean('ai_detector_feature')->nullable()->default(0);
            $table->boolean('plagiarism_feature')->nullable()->default(0);
            $table->integer('plagiarism_pages')->default(0);
            $table->integer('ai_detector_pages')->default(0);
            $table->boolean('personal_chats_feature')->nullable()->default(0);
            $table->boolean('personal_templates_feature')->nullable()->default(0);
            $table->string('voiceover_vendors')->nullable();
            $table->boolean('brand_voice_feature')->nullable()->default(0);
            $table->integer('file_result_duration')->default(-1);
            $table->integer('document_result_duration')->default(-1);
            $table->integer('dalle_images')->default(0);
            $table->integer('sd_images')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
