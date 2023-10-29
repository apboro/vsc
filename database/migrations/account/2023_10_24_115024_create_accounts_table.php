<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', static function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_id')->index();

            $table->bigInteger('amount')->default(0);
            $table->bigInteger('limit')->default(0);

            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')
                ->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
