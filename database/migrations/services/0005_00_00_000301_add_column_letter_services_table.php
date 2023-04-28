<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLetterServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedTinyInteger('letter_id')->nullable();

            $table->foreign('letter_id')->references('id')->on('dictionary_letters')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['letter_id']);
            $table->dropColumn('letter_id');
        });
    }
}
