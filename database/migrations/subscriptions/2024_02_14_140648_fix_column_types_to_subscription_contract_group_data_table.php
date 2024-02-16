<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixColumnTypesToSubscriptionContractGroupDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_contract_group_data', function (Blueprint $table) {
            $table->integer('girls_1_count')->nullable()->change();
            $table->integer('boys_1_count')->nullable()->change();
            $table->integer('girls_2_count')->nullable()->change();
            $table->integer('boys_2_count')->nullable()->change();
            $table->integer('girls_3_count')->nullable()->change();
            $table->integer('boys_3_count')->nullable()->change();
            $table->integer('ward_count')->nullable()->change();
            $table->integer('trainer_count')->nullable()->change();
            $table->integer('attendant_count')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_contract_group_data', function (Blueprint $table) {
            //
        });
    }
}
