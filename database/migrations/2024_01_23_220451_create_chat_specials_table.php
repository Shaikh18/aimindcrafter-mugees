<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmbeddingCollection;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_specials', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->bigInteger('user_id')->unsigned();
            $table->foreignIdFor(EmbeddingCollection::class)->onDelete('cascade');
            $table->text("title")->nullable();
            $table->text("url")->nullable();
            $table->integer('messages')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('chat_specials');
    }
};
