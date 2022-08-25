<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceSchedulesDaysAndTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('service_schedules', static function (Blueprint $table) {
            $table->boolean('mon')->nullable();
            $table->boolean('tue')->nullable();
            $table->boolean('wed')->nullable();
            $table->boolean('thu')->nullable();
            $table->boolean('fri')->nullable();
            $table->boolean('sat')->nullable();
            $table->boolean('sun')->nullable();
            $table->time('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('service_schedules', static function (Blueprint $table) {
            $table->dropColumn('mon');
            $table->dropColumn('tue');
            $table->dropColumn('wed');
            $table->dropColumn('thu');
            $table->dropColumn('fri');
            $table->dropColumn('sat');
            $table->dropColumn('sun');
            $table->dropColumn('start_time');
        });
    }
}
