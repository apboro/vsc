<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionContractDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscription_contract_data', static function (Blueprint $table) {
            $table->date('service_start_date')->nullable();
            $table->date('service_end_date')->nullable();
            $table->unsignedTinyInteger('trainings_per_week')->nullable();
            $table->unsignedTinyInteger('trainings_per_month')->nullable();
            $table->unsignedSmallInteger('training_duration')->nullable();
            $table->unsignedInteger('monthly_price')->nullable();
            $table->unsignedInteger('training_return_price')->nullable();
            $table->string('sport_kind')->nullable();
            $table->string('training_base_address')->nullable();
            $table->string('organization_title')->nullable();
            $table->string('organization_inn')->nullable();
            $table->string('organization_kpp')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_title')->nullable();
            $table->string('bank_bik')->nullable();
            $table->string('bank_ks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('subscription_contract_data', static function (Blueprint $table) {
            $table->dropColumn('service_start_date');
            $table->dropColumn('service_end_date');
            $table->dropColumn('trainings_per_week');
            $table->dropColumn('trainings_per_month');
            $table->dropColumn('training_duration');
            $table->dropColumn('monthly_price');
            $table->dropColumn('training_return_price');
            $table->dropColumn('sport_kind');
            $table->dropColumn('training_base_address');
            $table->dropColumn('organization_title');
            $table->dropColumn('organization_inn');
            $table->dropColumn('organization_kpp');
            $table->dropColumn('bank_account');
            $table->dropColumn('bank_title');
            $table->dropColumn('bank_bik');
            $table->dropColumn('bank_ks');
        });
    }
}
