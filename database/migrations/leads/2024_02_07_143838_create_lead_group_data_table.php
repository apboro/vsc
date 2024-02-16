<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadGroupDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_group_data', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lead_id');
            $table->boolean('is_contract_individual');
            $table->string('organization_name')->nullable();
            $table->boolean('is_trainer_needed')->default(false);
            $table->integer('girls_1_count')->nullable();
            $table->integer('boys_1_count')->nullable();
            $table->integer('girls_2_count')->nullable();
            $table->integer('boys_2_count')->nullable();
            $table->integer('girls_3_count')->nullable();
            $table->integer('boys_3_count')->nullable();
            $table->integer('ward_count');
            $table->integer('trainer_count');
            $table->integer('attendant_count');

            $table->foreign('lead_id')->references('id')->on('leads')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_group_data');
    }
}
