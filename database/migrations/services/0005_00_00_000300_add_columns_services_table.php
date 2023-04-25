<?php

use App\Models\Dictionaries\ServiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('type_program_id')->nullable();
            $table->unsignedTinyInteger('contract_id')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->text('description')->nullable();
            $table->date('date_deposit_funds')->nullable();
            $table->unsignedInteger('advance_payment')->nullable();
            $table->date('date_advance_payment')->nullable();
            $table->unsignedInteger('refund_amount')->nullable();
            $table->unsignedInteger('daily_price')->nullable();
            $table->unsignedInteger('price_deduction_advance')->nullable();

            $table->foreign('contract_id')->references('id')->on('dictionary_contracts')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('type_program_id')->references('id')->on('service_programs')->restrictOnDelete()->cascadeOnUpdate();
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
            $table->dropForeign(['type_program_id']);
            $table->dropForeign(['contract_id']);

            $table->dropColumn('type_program_id');
            $table->dropColumn('contract_id');
            $table->dropColumn('price');
            $table->dropColumn('description');
            $table->dropColumn('date_deposit_funds');
            $table->dropColumn('advance_payment');
            $table->dropColumn('date_advance_payment');
            $table->dropColumn('refund_amount');
            $table->dropColumn('daily_price');
            $table->dropColumn('price_deduction_advance');
        });
    }
}
