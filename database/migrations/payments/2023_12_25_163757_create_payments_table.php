<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('gate');
            $table->unsignedBigInteger('invoice_id');
            $table->string('external_payment_id')->nullable();
            $table->string('external_invoice_id')->nullable();
            $table->unsignedInteger('amount');
            $table->unsignedTinyInteger('status_id');
            $table->timestamps();

            $table->foreign('status_id')->on('dictionary_invoice_payment_statuses')
                ->references('id');
            $table->foreign('invoice_id')->on('invoices')
                ->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
