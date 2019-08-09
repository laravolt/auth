<?php

namespace Laravolt\Auth;

use Laravolt\Auth\Contracts\ForgotPassword;

class DefaultForgotPassword implements ForgotPassword
{
    public function getUserByIdentifier($identifier)
    {
        $identifierColumn = config('laravolt.auth.password.forgot.identifier', config('laravolt.auth.identifier'));

        return app(config('auth.providers.users.model'))
            ->query()
            ->where($identifierColumn, '=', $identifier)->first();
    }
}
