<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryClientCommentsTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_client_comments_type', function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true);

            $table->string('name');
            $table->boolean('enabled')->nullable()->default(true);
            $table->unsignedTinyInteger('order')->nullable()->default(0);

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
        Schema::dropIfExists('dictionary_client_comments_type');
    }
}
