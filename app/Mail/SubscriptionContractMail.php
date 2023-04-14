<?php

namespace App\Mail;

use App\Helpers\SubscriptionContractPdf;
use App\Models\Dictionaries\PatternLetters;
use App\Models\Subscriptions\SubscriptionContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionContractMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var SubscriptionContract To be processed */
    protected SubscriptionContract $contract;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SubscriptionContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SubscriptionContractMail
    {
        $this->contract->loadMissing('subscription');
        $this->contract->loadMissing('subscription.organization');
        $this->contract->loadMissing('subscription.client.user.profile');

        if (!isset($this->subscription->service->letter) || $this->subscription->service->letter->pattern_id === PatternLetters::regular) {
            $text = 'Ура, нашего полку прибыло! Рады будем увидеть Чемпиона в рядах своих воспитанников на ближайшей по расписанию тренировке. Напоминаем, что регулярные занятия можно оплатить по реквизитам, указанным в Договоре.';
        } else {
            $text = 'Ура, нашего полку прибыло! Рады будем увидеть Чемпиона в своих рядах. Напоминаем, о необходимости выполнять все условия Договора и приобрести билеты на тренировочное мероприятие.';
        }

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS'), $this->contract->subscription->organization->title)
            ->subject('Договор на оказание услуг')
            ->text('mail.subscriptions.contract.contract', [
                'text' => $text,
            ]);

        $mail->to(trim($this->contract->subscription->client->user->profile->email), $this->contract->subscription->client->user->profile->compactName);

        $mail->attachData(SubscriptionContractPdf::generate($this->contract, true), "Договор.pdf");

        return $mail;
    }
}
