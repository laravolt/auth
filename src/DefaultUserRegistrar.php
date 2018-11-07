<?php

namespace Laravolt\Auth;

use Illuminate\Support\Facades\Validator;
use Laravolt\Auth\Contracts\UserRegistrar;

class DefaultUserRegistrar implements UserRegistrar
{
    public function validate($data)
    {
        return Validator::make(
            $data,
            [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
            ]
        );
    }

    public function register($data)
    {
        $user = app(config('auth.providers.users.model'));
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->status = config('laravolt.auth.activation.enable') ?
            config('laravolt.auth.activation.status_before') :
            config('laravolt.auth.registration.status');
        $user->save();

        return $user;
    }
}
