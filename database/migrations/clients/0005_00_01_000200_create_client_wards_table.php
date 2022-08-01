<?php

use App\Models\Dictionaries\ClientWardStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientWardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('client_wards', static function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedTinyInteger('status_id')->default(ClientWardStatus::default);
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_client_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('client_wards');
    }
}
