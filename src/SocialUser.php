<?php
namespace Laravolt\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravolt\Auth\Models\SocialAccount;

class SocialUser
{

    private $socialite;
    private $auth;

    public function __construct(Socialite $socialite, Guard $auth)
    {
        $this->socialite = $socialite;
        $this->auth = $auth;
    }

    public function login(User $socialAcount, $provider)
    {
        $userClass = config('auth.model');

        $user = $userClass::whereEmail($socialAcount->getEmail())->first();

        if (!$user) {
            $user = $userClass::create([
                'name'     => $socialAcount->getName(),
                'email'    => $socialAcount->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'status'   => 'ACTIVE',
            ]);
        }

        $account = SocialAccount::firstOrCreate(['provider' => $provider, 'provider_id' => $socialAcount->getId()]);
        $user->socialAccounts()->save($account);

        $account->touch();

        $this->auth->login($user, true);
    }
}
