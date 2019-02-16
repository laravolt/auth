<?php

namespace Laravolt\Auth;

use Illuminate\Support\Facades\Mail;
use Laravolt\Auth\Mail\ActivationMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Laravolt\Auth\Contracts\UserRegistrar;
use Laravolt\Auth\Contracts\ShouldActivate;

class DefaultUserRegistrar implements UserRegistrar, ShouldActivate
{
    public function validate(array $data)
    {
        return Validator::make(
            $data,
            [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]
        );
    }

    public function register(array $data)
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

    public function notify(Model $user, $token)
    {
        Mail::to($user)->send(new ActivationMail($token));
    }

    public function activate($token)
    {
        $userIds = \DB::table('users_activation')->whereToken($token)->pluck('user_id');

        if ($userIds->isEmpty()) {
            abort(404);
        }

        $userId = $userIds->first();

        $user = app(config('auth.providers.users.model'))->findOrFail($userId);
        $user->status = config('laravolt.auth.activation.status_after');
        $user->save();

        \DB::table('users_activation')->whereUserId($userId)->delete();

        return redirect()->route('auth::login')->withSuccess(trans('auth::auth.activation_success'));
    }
}
