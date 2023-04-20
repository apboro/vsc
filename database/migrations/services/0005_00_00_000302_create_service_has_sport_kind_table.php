<?php

use App\Models\Services\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceHasSportKindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('service_has_sport_kind', static function (Blueprint $table) {

            $table->unsignedBigInteger('service_id');
            $table->unsignedSmallInteger('sport_kind_id');

            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('sport_kind_id')->references('id')->on('dictionary_sport_kinds')->cascadeOnDelete()->cascadeOnUpdate();
        });

        Service::query()->get()->map(function($service) {
            $service->sportKinds()->sync([$service->sport_kind_id]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('service_has_sport_kind');
    }
}
