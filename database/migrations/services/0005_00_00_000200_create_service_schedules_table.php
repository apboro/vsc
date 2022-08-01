<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('service_schedules', static function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->unique()->primary();

            $table->text('text')->nullable();

            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('service_schedules');
    }
}
