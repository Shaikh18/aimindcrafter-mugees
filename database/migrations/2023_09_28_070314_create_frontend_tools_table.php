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
        Schema::create('frontend_tools', function (Blueprint $table) {
            $table->id();
            $table->string('tool_code')->nullable();
            $table->string('tool_name')->nullable();
            $table->string('title')->nullable();
            $table->string('title_icon')->nullable();
            $table->string('title_meta')->nullable();
            $table->string('image')->nullable();
            $table->string('image_footer')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('status')->default(true)->nullable();
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
        Schema::dropIfExists('frontend_tools');
    }
};
