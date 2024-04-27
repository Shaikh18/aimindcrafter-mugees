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
        Schema::create('article_wizards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->text('titles')->nullable();
            $table->text('keywords')->nullable();
            $table->longText('outlines')->nullable();
            $table->longText('talking_points')->nullable();
            $table->integer('current_step')->default(1);
            $table->string('language')->nullable();
            $table->string('image')->nullable();
            $table->longText('image_description')->nullable();
            $table->string('tone')->nullable();
            $table->integer('max_words')->nullable();
            $table->float('creativity')->default(0.5);
            $table->string('view_point')->nullable();
            $table->boolean('status')->default(false);
            $table->longText('selected_keywords')->nullable();
            $table->longText('selected_title')->nullable();
            $table->longText('selected_outline')->nullable();
            $table->longText('selected_talking_points')->nullable();
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
        Schema::dropIfExists('article_wizards');
    }
};
