<?php

use App\Models\Dictionaries\LeadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('leads', static function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedTinyInteger('status_id')->default(LeadStatus::default);
            $table->unsignedSmallInteger('organization_id');

            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_lead_statuses')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('service_id')->references('id')->on('services')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id')->references('id')->on('clients')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
}
