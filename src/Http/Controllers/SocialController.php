<?php

namespace Laravolt\Auth\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use Laravolt\Auth\SocialUser;

class SocialController extends Controller
{
    public function login($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(SocialUser $socialUser, $provider)
    {
        $user = Socialite::driver($provider)->user();
        $socialUser->login($user, $provider);

        return redirect('/');
    }
}
