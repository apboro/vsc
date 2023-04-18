<?php

namespace App\Classes;

use Illuminate\Notifications\Notifiable;

class EmailReceiver
{
    use Notifiable;

    /** @var string Receiver email */
    protected string $email;

    /** @var string|null Receiver name */
    protected ?string $name;

    /**
     * @param string $email
     * @param string|null $name
     *
     * @return  void
     */
    public function __construct(string $email, ?string $name = null)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return array|string
     * @noinspection PhpUnused
     */
    public function routeNotificationForMail()
    {
        return empty($this->name) ? $this->email : [$this->email => $this->name];
    }
}
