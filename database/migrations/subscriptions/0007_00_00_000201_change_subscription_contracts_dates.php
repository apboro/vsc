<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSubscriptionContractsDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscription_contracts', static function (Blueprint $table) {
            $table->date('start_at')->nullable()->change();
            $table->date('end_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('subscription_contracts', static function (Blueprint $table) {
            $table->date('start_at')->change();
            $table->date('end_at')->change();
        });
    }
}
