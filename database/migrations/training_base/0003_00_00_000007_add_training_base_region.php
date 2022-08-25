<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrainingBaseRegion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('training_bases', static function (Blueprint $table) {
            $table->unsignedSmallInteger('region_id')->nullable();

            $table->foreign('region_id')->references('id')->on('dictionary_regions')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('training_bases', static function (Blueprint $table) {
            $table->dropForeign(['region_id']);

            $table->dropColumn('region_id');
        });
    }
}
