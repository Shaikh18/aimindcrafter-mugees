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
        Schema::create('custom_chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('sub_name')->nullable();
            $table->string('logo')->nullable();
            $table->longText('description')->nullable(); 
            $table->string('chat_code')->nullable(); 
            $table->boolean('status')->default(true); 
            $table->boolean('code_interpreter')->default(false); 
            $table->boolean('retrieval')->default(false); 
            $table->boolean('function')->default(false); 
            $table->string('group')->nullable(); 
            $table->string('type')->default('private'); 
            $table->string('model')->default('gpt-3.5-turbo-0125'); 
            $table->longText('prompt')->nullable(); 
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
        Schema::dropIfExists('custom_chats');
    }
};
