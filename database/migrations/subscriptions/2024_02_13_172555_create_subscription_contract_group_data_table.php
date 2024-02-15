<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionContractGroupDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_contract_group_data', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_contract_id')->unique();
            $table->primary('subscription_contract_id', 'sub_con_group_data_sub_con_id_pr');

            $table->string('girls_1_count')->nullable();
            $table->string('boys_1_count')->nullable();
            $table->string('girls_2_count')->nullable();
            $table->string('boys_2_count')->nullable();
            $table->string('girls_3_count')->nullable();
            $table->string('boys_3_count')->nullable();
            $table->string('ward_count')->nullable();
            $table->string('trainer_count')->nullable();
            $table->string('attendant_count')->nullable();

            $table->timestamps();

            $table->foreign('subscription_contract_id', 'sub_con_group_fk')->references('id')->on('subscription_contracts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_contract_group_data');
    }
}
