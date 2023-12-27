<?php

namespace App\Jobs\Invoices;

use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\InvoiceType;
use App\Models\Subscriptions\SubscriptionContract;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CreateInvoicesJob implements ShouldQueue
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
        $firstDayOfLastMonth = (new Carbon('first day of last month'))->toDate();
        $lastDayOfLastMonth = (new Carbon('last day of last month'))->toDate();

        /** @var Collection<SubscriptionContract> $activeContracts */
        $activeContracts = SubscriptionContract::active()
            ->get();

        /** @var SubscriptionContract $contract */
        foreach ($activeContracts as $contract) {
            $moderationRequired = $contract->subscription->client->account->amount > 0;

            if ($contract->invoices()->whereDate('date_from', '>=', $firstDayOfLastMonth)->count() > 0) {
                continue;
            }

            try {
                $contract->invoices()->create([
                    'date_from' => $firstDayOfLastMonth,
                    'date_to' => $lastDayOfLastMonth,
                    'moderation_required' => $moderationRequired,
                    'status_id' => $moderationRequired ? InvoiceStatus::draft : InvoiceStatus::ready,
                    'type_id' => InvoiceType::base,
                    'amount_to_pay' => $contract->calculateBaseInvoiceTotal(),
                    'comment' => 'Автоматический созданный счет по контракту ' . $contract->id,
                ]);
            } catch (Exception $e) {

            }
        }
    }
}
