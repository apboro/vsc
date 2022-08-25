<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceRequisites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->unsignedSmallInteger('requisites_id')->nullable();

            $table->foreign('requisites_id')->references('id')->on('dictionary_organization_requisites')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->dropForeign(['requisites_id']);

            $table->dropColumn('requisites_id');
        });
    }
}
