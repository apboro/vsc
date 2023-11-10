<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->text('comment')->nullable();

            $table->dateTime('date_from');
            $table->dateTime('date_to');

            $table->boolean('moderation_required')->default(false);

            $table->bigInteger('amount_to_pay');
            $table->bigInteger('amount_paid')->nullable();

            $table->dateTime('paid_at')->nullable();

            $table->unsignedBigInteger('contract_id');

            $table->unsignedTinyInteger('status_id');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('payment_type_id')->nullable();

            $table->foreign('contract_id')->references('id')->on('subscription_contracts')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('status_id')->references('id')->on('dictionary_invoice_statuses')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('type_id')->references('id')->on('dictionary_invoice_types')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('payment_type_id')->references('id')->on('dictionary_invoice_payment_types')
                ->cascadeOnUpdate()->restrictOnDelete();

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
        Schema::dropIfExists('invoices');
    }
}
