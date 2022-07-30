<?php

use App\Models\Dictionaries\ServiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('services', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('status_id')->default(ServiceStatus::default);

            $table->unsignedSmallInteger('organization_id');
            $table->unsignedInteger('training_base_id');
            $table->unsignedSmallInteger('sport_kind_id');

            $table->unsignedInteger('monthly_price');

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_service_statuses')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
}
