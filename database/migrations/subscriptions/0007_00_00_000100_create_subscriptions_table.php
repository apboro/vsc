<?php

use App\Models\Dictionaries\SubscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('subscriptions', static function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedTinyInteger('status_id')->default(SubscriptionStatus::default);
            $table->unsignedSmallInteger('organization_id');

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('client_ward_id')->nullable();

            $table->unsignedBigInteger('service_id');

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_subscription_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id')->references('id')->on('clients')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('client_ward_id')->references('id')->on('client_wards')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('service_id')->references('id')->on('services')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
}
