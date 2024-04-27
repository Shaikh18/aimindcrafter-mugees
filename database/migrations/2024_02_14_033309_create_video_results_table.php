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
        Schema::create('video_results', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('image');
            $table->longText('video')->nullable();
            $table->string('job_id')->nullable();          
            $table->string('storage')->nullable();
            $table->string('status')->nullable();
            $table->integer('seed')->nullable();
            $table->integer('cfg_scale')->nullable();
            $table->integer('motion_bucket_id')->nullable();
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
        Schema::dropIfExists('video_results');
    }
};
