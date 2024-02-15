<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToContractDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_contract_data', function (Blueprint $table) {
            $table->integer('days_count')->nullable();
            $table->unsignedInteger('group_price')->nullable();
            $table->unsignedInteger('additional_price')->nullable();
            $table->unsignedInteger('total_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_contract_data', function (Blueprint $table) {
            $table->dropColumn(['days_count', 'group_price', 'additional_price', 'total_price']);
        });
    }
}
