<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingBaseContractHasFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('training_base_contract_has_file', static function (Blueprint $table) {
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('file_id');

            $table->foreign('contract_id')->references('id')->on('training_base_contracts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('file_id')->references('id')->on('files')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('training_base_contract_has_file');
    }
}
