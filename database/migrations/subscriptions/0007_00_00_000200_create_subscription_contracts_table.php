<?php

use App\Models\Dictionaries\SubscriptionContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('subscription_contracts', static function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedTinyInteger('status_id')->default(SubscriptionContractStatus::default);
            $table->unsignedBigInteger('subscription_id');

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_subscription_contract_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_contracts');
    }
}
