<?php

namespace Laravolt\Auth;

use Laravolt\Auth\Contracts\ForgotPassword;

class DefaultResetPassword implements ForgotPassword
{
    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function getUserByIdentifier($identifier)
    {
        $identifierColumn = config('laravolt.auth.password.reset.identifier') ?? config('laravolt.auth.identifier');

        return app(config('auth.providers.users.model'))
            ->query()
            ->where($identifierColumn, '=', $identifier)->first();
    }
}
