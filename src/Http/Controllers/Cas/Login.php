<?php

namespace Laravolt\Auth\Http\Controllers\Cas;

use Illuminate\Http\Request;

class Login extends CasController
{
    public function __invoke(Request $request)
    {
        cas()->authenticate();

        $email = (config('cas.cas_masquerade')) ? cas()->user() : array_get(cas()->getAttributes(), 'email');
        $user = app(config('auth.providers.users.model'))->whereEmail($email)->first();
        $loginService = app('laravolt.auth.login');

        if ($user && auth()->login($user)) {
            if (method_exists($loginService, 'authenticated')) {
                $request->merge(['_auth' => 'cas']);

                return $loginService->authenticated($request, $user);
            }

            return redirect()->home();
        } else {
            return redirect()->route('auth::login')->withError(trans('auth::login_cas_failed'));
        }
    }
}
