<?php

use App\Models\Dictionaries\TrainingBaseContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingBaseContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('training_base_contracts', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('status_id')->default(TrainingBaseContractStatus::default);
            $table->unsignedInteger('training_base_id');

            $table->date('start_at');
            $table->date('end_at');

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_training_base_contract_statuses')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('training_base_id')->references('id')->on('training_bases')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('training_base_contracts');
    }
}
