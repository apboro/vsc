<?php

use App\Models\Dictionaries\SubscriptionContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSubscriptionContractDataForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscription_contract_data', static function (Blueprint $table) {
            $table->dropForeign(['subscription_contract_id']);
            $table->foreign('subscription_contract_id')->references('id')->on('subscription_contracts')->cascadeOnDelete()->cascadeOnUpdate();
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
            $table->dropForeign(['subscription_contract_id']);
            $table->foreign('subscription_contract_id')->references('id')->on('subscription_contracts')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
}
