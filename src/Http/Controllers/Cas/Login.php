<?php

namespace Laravolt\Auth\Http\Controllers\Cas;

class Login extends CasController
{
    public function __invoke()
    {
        cas()->authenticate();

        $email = (config('cas.cas_masquerade')) ? cas()->user() : array_get(cas()->getAttributes(), 'email');
        $user = app(config('auth.providers.users.model'))->whereEmail($email)->first();

        if ($user && auth()->login($user)) {
            return redirect()->home();
        } else {
            return redirect()->route('auth::login')->withError(trans('auth::login_cas_failed'));
        }
    }
}
