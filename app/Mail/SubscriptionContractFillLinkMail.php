<?php

namespace App\Mail;

use App\Models\Subscriptions\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionContractFillLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Subscription To be processed */
    protected Subscription $subscription;

    /** @var string|null Comments to send to client */
    protected ?string $comments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, ?string $comments = null)
    {
        $this->subscription = $subscription;
        $this->comments = $comments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SubscriptionContractFillLinkMail
    {
        $this->subscription->loadMissing([
            'organization',
            'client.user.profile',
            'service.letter.pattern',
            'service.trainingBase.info',
        ]);

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS'), $this->subscription->organization->title)
            ->subject('Ссылка на форму заполнения договора')
            ->text($this->subscription->service->letter->pattern->link, [
                'subscription' => $this->subscription,
                'comments' => "\n" . $this->comments,
            ]);

        $mail->to(trim($this->subscription->client->user->profile->email), $this->subscription->client->user->profile->compactName);

        return $mail;
    }
}
