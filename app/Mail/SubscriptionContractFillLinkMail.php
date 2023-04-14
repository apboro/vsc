<?php

namespace App\Mail;

use App\Models\Dictionaries\PatternLetters;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\Subscriptions\Subscription;
use Illuminate\Bus\Queueable;
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
        $this->subscription->loadMissing([
            'organization',
            'client.user.profile',
            'service.letter',
            'service.trainingBase.info',
        ]);

        $id = $this->subscription->id;

        if (!isset($this->subscription->service->letter) || $this->subscription->service->letter->pattern_id === PatternLetters::regular) {
            $lines = $this->regularLines($id);
        } else {
            $lines = $this->singleLines($id);
        }

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS'), $this->subscription->organization->title)
            ->subject('Ссылка на форму заполнения договора')
            ->text('mail.subscriptions.contract.form_link', [
                'lines' => $lines,
                'comments' => "\n" . $this->comments,
            ]);

        $mail->to(trim($this->subscription->client->user->profile->email), $this->subscription->client->user->profile->compactName);

        return $mail;
    }

    private function regularLines($id): array
    {
        return [
            'Здравствуйте, родители (законные представители), будущего Чемпиона/Чемпионки!',
            'Нам с Вами осталось заполнить договор, перейдя по ссылке:',
            route('leads.subscription.contract.fill', ['sbsc' => Crypt::encrypt($id)]) . ', и мы можем начинать тренировки/тренировочные мероприятия/соревнования. Точнее, наши сотрудники - тренировать, Вы сопровождать и встречать будущего Чемпиона (воспитанника), а будущий Чемпион - сможет начинать побеждать.',

            'Наши контакты для связи',
            '- группа в контакте: ' . $this->subscription->service->trainingBase->info->homepage,
            '- телефон: '. $this->subscription->service->trainingBase->info->phone,
            '- почта: '. $this->subscription->service->trainingBase->info->email,
            '- сайт: vsev-sportcenter.ru',
            'В начале учебного года обращений много, мы отвечаем с той скоростью на которую физически способны.',
            'Благодарим за понимание!',
            'Ваша команда Центра Школьного Спорта',
        ];
    }

    private function singleLines($id): array
    {
        return [
            'Здравствуйте, родители (законные представители), будущего Чемпиона/Чемпионки!',
            'Нам с Вами осталось заполнить договор, перейдя по ссылке:',
            route('leads.subscription.contract.fill', ['sbsc' => Crypt::encrypt($id)]) . ', и мы можем начинать тренировки/тренировочные мероприятия/соревнования. Точнее, наши сотрудники - тренировать, Вы сопровождать и встречать будущего Чемпиона (воспитанника), а будущий Чемпион - сможет начинать побеждать.',

            'Обращаем Ваше внимание, что даже на сказочных тренировочных мероприятиях ребенок имеет право скучать по родителям. Просим отнестись к этому с пониманием. ',

            'Наши контакты для связи',
            '- группа в контакте: ' . $this->subscription->service->trainingBase->info->homepage,
            '- телефон: '. $this->subscription->service->trainingBase->info->phone,
            '- почта: '. $this->subscription->service->trainingBase->info->email,
            '- сайт: vsev-sportcenter.ru',
            'Обращений много, мы отвечаем с той скоростью на которую физически способны.',

            'Благодарим за понимание!',
            'Ваша команда Центра Школьного Спорта',
        ];
    }
}
