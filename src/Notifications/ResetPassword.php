<?php

namespace Laravolt\Auth\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
    protected $options = ['view' => 'auth::emails.reset'];

    /**
     * Create a notification instance.
     *
     * @param  string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @deprecated
     * Get the notification message.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\MessageBuilder
     */
    public function message($notifiable)
    {
        return $this->line(trans('auth::reset.intro'))
            ->action(
                trans('auth::auth.reset_password'),
                route('auth::reset', ['token' => $this->token, 'email' => urlencode($notifiable->email)])
            )
            ->line(trans('auth::reset.outro'));
    }

    /**
     * Get the notification message.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\MessageBuilder
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line(trans('auth::reset.intro'))
            ->action(
                trans('auth::auth.reset_password'),
                route('auth::reset', ['token' => $this->token, 'email' => urlencode($notifiable->email)])
            )
            ->line(trans('auth::reset.outro'));
    }
}
