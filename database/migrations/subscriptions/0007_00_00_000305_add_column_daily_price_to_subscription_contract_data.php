<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDailyPriceToSubscriptionContractData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscription_contract_data', static function (Blueprint $table) {
            $table->unsignedInteger('daily_price')->nullable();
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
            $table->dropColumn('daily_price');
        });
    }
}
