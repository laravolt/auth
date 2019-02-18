<?php

namespace Laravolt\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Laravolt\Auth\Mail\ActivationMail;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Auth\Contracts\ShouldActivate;

trait Activation
{
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        $token = $this->createToken($user);

        $this->notifyForActivation($user, $token);

        return $this->registered($request, $user) ?:
            redirect()->back()->withSuccess(trans('auth::auth.registration_success'));
    }

    public function activate($token)
    {
        return app('laravolt.auth.registrar')->activate($token);
    }

    protected function createToken(Model $user)
    {
        $token = md5(uniqid(rand(), true));
        DB::table('users_activation')->insert([
            'user_id'    => $user->getKey(),
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }

    protected function notifyForActivation($user, $token)
    {
        app('laravolt.auth.registrar')->notify($user, $token);
    }
}
