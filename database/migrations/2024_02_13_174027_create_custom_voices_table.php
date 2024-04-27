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
        Schema::create('custom_voices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('voice');
            $table->string('voice_id');
            $table->string('gender')->nullable();          
            $table->string('vendor_id')->default('elevenlabs_nrl')->nullable();
            $table->string('vendor')->default('elevenlabs')->nullable();
            $table->string('vendor_img')->default('/img/csp/elevenlabs-sm.png')->nullable();
            $table->string('status')->default('active');
            $table->string('avatar_url')->nullable();
            $table->string('voice_type')->default('custom')->nullable();
            $table->string('sample_url')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('custom_voices');
    }
};
