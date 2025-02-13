<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionHasPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('position_has_permission', static function (Blueprint $table) {

            $table->unsignedInteger('position_id');
            $table->unsignedSmallInteger('permission_id');

            $table->foreign('position_id')->references('id')->on('positions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('permission_id')->references('id')->on('permissions')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('position_has_permission');
    }
}
