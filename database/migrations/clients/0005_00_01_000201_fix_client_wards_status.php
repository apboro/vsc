<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixClientWardsStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('client_wards', static function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->foreign('status_id')->references('id')->on('dictionary_client_ward_statuses')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('client_wards', static function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->foreign('status_id')->references('id')->on('dictionary_client_statuses')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
}
