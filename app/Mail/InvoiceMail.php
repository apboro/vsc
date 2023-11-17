<?php

namespace App\Mail;

use App\Models\Invoices\Invoice;
use App\Models\Subscriptions\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Invoice To be processed */
    protected Invoice $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): InvoiceMail
    {
        $textView = 'mail.subscriptions.contract.contract_single';

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS'), $this->invoice->contract->subscription->organization->title)
            ->subject('Договор на оказание услуг')
            ->text($textView);

        $mail->to(trim($this->invoice->contract->subscription->client->user->profile->email), $this->invoice->contract->subscription->client->user->profile->compactName);

//        $mail->attachData(SubscriptionContractPdf::generate($this->contract, true), "Договор.pdf");

        return $mail;
    }
}
