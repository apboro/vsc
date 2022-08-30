<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionContractMoreDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscription_contract_data', static function (Blueprint $table) {
            $table->string('ward_passport_serial')->nullable();
            $table->string('ward_passport_number')->nullable();
            $table->string('ward_passport_place')->nullable();
            $table->date('ward_passport_date')->nullable();
            $table->string('ward_passport_code')->nullable();
            $table->string('ward_registration_address')->nullable();
            $table->date('birth_date')->nullable();
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
            $table->dropColumn('ward_passport_serial');
            $table->dropColumn('ward_passport_number');
            $table->dropColumn('ward_passport_place');
            $table->dropColumn('ward_passport_date');
            $table->dropColumn('ward_passport_code');
            $table->dropColumn('ward_registration_address');
            $table->dropColumn('birth_date');
        });
    }
}
