<?php

namespace Laravolt\Auth;

use Illuminate\Http\Request;
use Laravolt\Auth\Contracts\Login;

class DefaultLogin implements Login
{
    public function rules(Request $request)
    {
        $rules = [
            $this->identifier() => 'required',
            'password'          => 'required',
        ];

        if (config('laravolt.auth.captcha')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return $rules;
    }

    public function credentials(Request $request)
    {
        return $request->only($this->identifier(), 'password');
    }

    public function loggedOut(Request $request)
    {
        return redirect()->to(config('laravolt.auth.redirect.after_logout', '/'));
    }

    protected function identifier()
    {
        return config('laravolt.auth.identifier');
    }

}
