<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryOrganizationRequisitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('dictionary_organization_requisites', static function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->string('name');
            $table->boolean('enabled')->nullable()->default(true);
            $table->unsignedTinyInteger('order')->nullable()->default(0);
            $table->unsignedSmallInteger('organization_id');

            $table->string('organization_title')->nullable();
            $table->string('organization_inn')->nullable();
            $table->string('organization_kpp')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_title')->nullable();
            $table->string('bank_bik')->nullable();
            $table->string('bank_ks')->nullable();

            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionary_organization_requisites');
    }
}
