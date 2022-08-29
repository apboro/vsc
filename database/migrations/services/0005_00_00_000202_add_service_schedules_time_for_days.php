<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceSchedulesTimeForDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('service_schedules', static function (Blueprint $table) {
            $table->renameColumn('start_time', 'mon_start_time');
            $table->time('tue_start_time')->nullable();
            $table->time('wed_start_time')->nullable();
            $table->time('thu_start_time')->nullable();
            $table->time('fri_start_time')->nullable();
            $table->time('sat_start_time')->nullable();
            $table->time('sun_start_time')->nullable();
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
            $table->renameColumn('mon_start_time', 'start_time');
            $table->dropColumn('tue_start_time');
            $table->dropColumn('wed_start_time');
            $table->dropColumn('thu_start_time');
            $table->dropColumn('fri_start_time');
            $table->dropColumn('sat_start_time');
            $table->dropColumn('sun_start_time');
        });
    }
}
