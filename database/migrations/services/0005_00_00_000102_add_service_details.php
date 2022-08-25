<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->unsignedTinyInteger('trainings_per_month')->nullable();
            $table->unsignedInteger('training_return_price')->nullable();
            $table->unsignedSmallInteger('training_duration')->nullable();
            $table->unsignedSmallInteger('group_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->dropColumn('trainings_per_month');
            $table->dropColumn('training_return_price');
            $table->dropColumn('training_duration');
            $table->dropColumn('group_limit');
        });
    }
}
