<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionContractMoreDetailsAdvance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscription_contract_data', static function (Blueprint $table) {
            $table->string('service_name')->nullable();
            $table->string('training_base_name')->nullable();
            $table->unsignedInteger('advance_payment')->nullable();
            $table->date('date_advance_payment')->nullable();
            $table->date('date_deposit_funds')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('refund_amount')->nullable();
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
            $table->dropColumn('service_name');
            $table->dropColumn('training_base_name');
            $table->dropColumn('advance_payment');
            $table->dropColumn('date_advance_payment');
            $table->dropColumn('date_deposit_funds');
            $table->dropColumn('price');
            $table->dropColumn('refund_amount');
        });
    }
}
