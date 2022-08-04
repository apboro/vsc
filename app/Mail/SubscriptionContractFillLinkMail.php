<?php

namespace App\Mail;

use App\Models\Subscriptions\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class SubscriptionContractFillLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Subscription To be processed */
    protected Subscription $subscription;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SubscriptionContractFillLinkMail
    {
        $this->subscription->loadMissing('client.user.profile');
        $this->subscription->loadMissing('organization');

        $id = $this->subscription->id;

        $link = route('leads.subscription.contract.fill', ['sbsc' => Crypt::encrypt($id)]);

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS'), $this->subscription->organization->title)
            ->subject('Ссылка на форму заполнения договора')
            ->text('mail.subscriptions.contract.form_link', [
                'link' => $link,
            ]);

        $mail->to($this->subscription->client->user->profile->email, $this->subscription->client->user->profile->compactName);

        return $mail;
    }
}
