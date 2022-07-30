<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('organization_info', static function (Blueprint $table) {

            $table->unsignedSmallInteger('organization_id')->unique()->primary();

            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_info');
    }
}
