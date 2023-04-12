<?php

use App\Models\Dictionaries\ServiceCategories;
use App\Models\Dictionaries\ServiceTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('types_programs', static function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedTinyInteger('service_type_id')->default(ServiceTypes::regular);
            $table->unsignedTinyInteger('service_category_id')->default(ServiceCategories::paid);
            $table->unsignedSmallInteger('organization_id');
            $table->string('name');
            $table->unsignedTinyInteger('order')->nullable()->default(0);
            $table->boolean('enabled')->nullable()->default(true);

            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('service_type_id')->references('id')->on('dictionary_service_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('service_category_id')->references('id')->on('dictionary_service_categories')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('types_programs');
    }
}
