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
        $this->subscription->loadMissing('client.user.profile');
        $this->subscription->loadMissing('organization');

        $id = $this->subscription->id;

        $link = route('leads.subscription.contract.fill', ['sbsc' => Crypt::encrypt($id)]);

        $lines = [
            'Здравствуйте, родители (законные представители), будущего Чемпиона/Чемпионки!',
            'Нам с Вами осталось заполнить договор, перейдя по ссылке:',
            $link . ', и мы можем начинать тренировки/тренировочные мероприятия/соревнования. Точнее, наши сотрудники - тренировать, Вы сопровождать и встречать будущего Чемпиона (воспитанника), а будущий Чемпион - сможет начинать побеждать.',
            'Все вопросы можно задать на email kudrovo.sport.doc@yandex.ru. Работает и группа в ВК, и телефоны, указанные в договоре. До встречи.',
        ];

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS'), $this->subscription->organization->title)
            ->subject('Ссылка на форму заполнения договора')
            ->text('mail.subscriptions.contract.form_link', [
                'lines' => $lines,
                'comments' => "\n" . $this->comments,
            ]);

        $mail->to($this->subscription->client->user->profile->email, $this->subscription->client->user->profile->compactName);

        return $mail;
    }
}
