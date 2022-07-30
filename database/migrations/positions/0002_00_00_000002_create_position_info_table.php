<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('position_info', static function (Blueprint $table) {
            $table->unsignedInteger('position_id')->unique()->primary();

            $table->string('work_phone')->nullable();
            $table->string('work_phone_additional')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('position_id')->references('id')->on('positions')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('position_info');
    }
}
