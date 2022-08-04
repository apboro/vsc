<?php

use App\Models\Dictionaries\SubscriptionContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionContractDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('subscription_contract_data', static function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_contract_id')->unique()->primary();

            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('passport_serial')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport_place')->nullable();
            $table->date('passport_date')->nullable();
            $table->string('passport_code')->nullable();
            $table->string('registration_address')->nullable();
            $table->string('ward_lastname')->nullable();
            $table->string('ward_firstname')->nullable();
            $table->string('ward_patronymic')->nullable();
            $table->date('ward_birth_date')->nullable();
            $table->string('ward_document')->nullable();
            $table->date('ward_document_date')->nullable();

            $table->timestamps();

            $table->foreign('subscription_contract_id')->references('id')->on('subscription_contracts')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_contract_data');
    }
}
