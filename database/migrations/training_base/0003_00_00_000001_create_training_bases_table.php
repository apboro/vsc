<?php

use App\Models\Dictionaries\TrainingBaseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('training_bases', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('status_id')->default(TrainingBaseStatus::default);
            $table->unsignedSmallInteger('organization_id');

            $table->string('title');
            $table->string('short_title')->nullable();

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_training_base_statuses')->restrictOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('training_bases');
    }
}
