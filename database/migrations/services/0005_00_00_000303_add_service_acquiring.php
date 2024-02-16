<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceAcquiring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('services', static function (Blueprint $table) {
            $table->unsignedTinyInteger('acquiring_id')->nullable();

            $table->foreign('acquiring_id')->references('id')
                ->on('dictionary_acquiring')
                ->restrictOnDelete()->cascadeOnUpdate();
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
            $table->dropForeign(['acquiring_id']);

            $table->dropColumn('acquiring_id');
        });
    }
}
