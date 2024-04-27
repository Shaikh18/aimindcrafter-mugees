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
        Schema::create('fine_tunes', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->string("base_model");
            $table->string("file_id");
            $table->integer("bytes");
            $table->string("model_name")->nullable();
            $table->string("file_name");
            $table->string("status");
            $table->string("result_model")->nullable();
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
        Schema::dropIfExists('fine_tunes');
    }
};
