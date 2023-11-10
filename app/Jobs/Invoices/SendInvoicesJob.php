<?php

namespace App\Jobs\Invoices;

use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Invoices\Invoice;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Collection<Invoice> $invoices */
        $invoices = Invoice::query()
            ->whereHas('contract', function(Builder $q) {
                $q->active();
            })
            ->where('status_id', InvoiceStatus::ready)
            ->get();

        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            try {
                $invoice->sendToClient();

                $invoice->status_id = InvoiceStatus::sent;
                $invoice->save();
            } catch (Exception $e) {

            }
        }
    }
}
