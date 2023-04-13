<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('dictionary_patterns', static function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true);
            $table->string('name');
            $table->text('pattern')->nullable();
            $table->boolean('enabled')->nullable()->default(true);
            $table->unsignedTinyInteger('order')->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionary_patterns');
    }
}
