<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dictionary_acquiring', function (Blueprint $table) {
            $table->dropColumn('bank');
            $table->unsignedTinyInteger('bank_id')->nullable();

            $table->foreign('bank_id')->on('dictionary_banks')
                ->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('dictionary_acquiring', function (Blueprint $table) {
            $table->string('bank');
            $table->dropConstrainedForeignId('bank_id');
        });
    }
};
