<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingBaseHasSportKindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('training_base_has_sport_kinds', static function (Blueprint $table) {
            $table->unsignedInteger('training_base_id');
            $table->unsignedSmallInteger('sport_kind_id');

            $table->timestamps();

            $table->foreign('training_base_id')->references('id')->on('training_bases')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('sport_kind_id')->references('id')->on('dictionary_sport_kinds')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('training_base_has_sport_kinds');
    }
}
