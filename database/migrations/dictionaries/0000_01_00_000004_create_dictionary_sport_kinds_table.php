<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionarySportKindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('dictionary_sport_kinds', static function (Blueprint $table) {
            $table->smallIncrements('id')->from(1000);
            $table->string('name');
            $table->boolean('enabled')->nullable()->default(true);
            $table->unsignedSmallInteger('order')->nullable()->default(0);
            $table->unsignedSmallInteger('organization_id');

            $table->timestamps();
            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionary_sport_kinds');
    }
}
