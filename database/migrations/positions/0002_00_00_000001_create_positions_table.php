<?php

use App\Models\Dictionaries\PositionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('positions', static function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->unsignedSmallInteger('user_id');
            $table->unsignedTinyInteger('status_id')->default(PositionStatus::default);
            $table->unsignedSmallInteger('title_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreign('status_id')->references('id')->on('dictionary_position_statuses')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('title_id')->references('id')->on('dictionary_position_titles')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
}
