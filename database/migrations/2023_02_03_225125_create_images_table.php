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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('image')->nullable();
            $table->string('storage')->nullable();
            $table->string('image_name')->nullable();
            $table->string('resolution')->nullable();
            $table->string('plan_type')->comment('free|paid')->default('free');
            $table->dateTime('expires_at')->nullable();
            $table->string('vendor')->default('dalle')->nullable();
            $table->longText('negative_prompt')->nullable();
            $table->string('image_style')->nullable();
            $table->string('image_lighting')->nullable();
            $table->string('image_medium')->nullable();
            $table->string('image_mood')->nullable();
            $table->string('image_artist')->nullable();
            $table->string('sd_prompt_strength')->nullable();
            $table->string('sd_steps')->nullable();
            $table->string('sd_diffusion_samples')->nullable();
            $table->string('sd_clip_guidance')->nullable();
            $table->string('vendor_engine')->nullable();
            $table->boolean('public')->default(false);
            $table->boolean('favorite')->default(false);
            $table->integer('views')->nullable();
            $table->integer('downloads')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
};
