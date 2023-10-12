<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);

            $table->text('text');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('action_id')->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->unsignedBigInteger('client_id');

            $table->foreign('type_id')->references('id')->on('dictionary_client_comments_type')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('action_id')->references('id')->on('dictionary_client_comment_action_types')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('position_id')->references('id')->on('positions')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('client_id')->references('id')->on('clients')
                ->cascadeOnUpdate()->restrictOnDelete();

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
        Schema::dropIfExists('client_comments');
    }
}
