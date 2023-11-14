<?php

use App\Models\Dictionaries\InvoicePaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusFieldToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedTinyInteger('payment_status_id')->nullable();

            $table->foreign('payment_status_id')->references('id')->on('dictionary_invoice_payment_statuses')
                ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['payment_status_id']);

            $table->dropColumn('payment_status_id');
        });
    }
}
