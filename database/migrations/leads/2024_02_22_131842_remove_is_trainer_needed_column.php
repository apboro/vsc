<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsTrainerNeededColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_group_data', function (Blueprint $table) {
            $table->dropColumn('is_trainer_needed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_group_data', function (Blueprint $table) {
            $table->boolean('is_trainer_needed')->default(false);
        });
    }
}
