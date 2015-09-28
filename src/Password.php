<?php
namespace Laravolt\Auth;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Password as BasePassword;

class Password
{
    /**
     * @var Mailer
     */
    private $mailer;


    /**
     * Password constructor.
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendResetLink($user)
    {
        $response = BasePassword::sendResetLink(['id' => $user['id']], function (Message $message) {
            $message->subject(trans('passwords.reset'));
        });

        return $response;
    }

    public function sendNewPassword(CanResetPassword $user)
    {
        $password = str_random(8);
        $user->password = $password;
        $user->save();

        $this->mailer->send('auth::emails.new_password', compact('user', 'password'), function($m) use ($user) {
            $m->to($user->getEmailForPasswordReset());
        });
    }

}
