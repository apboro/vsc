<?php

use App\Models\Dictionaries\OrganizationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('organizations', static function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->unsignedTinyInteger('status_id')->default(OrganizationStatus::default);

            $table->string('title')->nullable();

            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('dictionary_organization_statuses')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
}
