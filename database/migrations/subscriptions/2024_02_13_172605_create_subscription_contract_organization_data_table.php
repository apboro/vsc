<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionContractOrganizationDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_contract_organization_data', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_contract_id')->unique('sub_con_org_data_unique');
            $table->primary('subscription_contract_id', 'sub_con_org_data_sub_con_id_pr');

            $table->string('organization_name')->nullable();
            $table->string('in_face')->nullable();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('checking_account')->nullable();
            $table->string('bic')->nullable();
            $table->string('corr_account')->nullable();
            $table->string('org_email')->nullable();
            $table->string('org_phone')->nullable();

            $table->timestamps();

            $table->foreign('subscription_contract_id', 'sub_con_data_fk')->references('id')->on('subscription_contracts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_contract_organization_data');
    }
}
