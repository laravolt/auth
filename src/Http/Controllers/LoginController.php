<?php

namespace Laravolt\Auth\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravolt\Auth\Services\LdapService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide this functionality to your appliations.
    |
    */

    use ValidatesRequests;
    use AuthenticatesUsers {
        login as defaultLogin;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Whether LDAP authentication enabled or not
     *
     * @var boolean
     */
    protected $ldapEnabled = false;

    /**
     * The custom login contract instance.
     *
     * @var \Laravolt\Auth\Contracts\Login
     */
    protected $login;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

        $this->redirectTo = config('laravolt.auth.redirect.after_login');

        $this->ldapEnabled = config('laravolt.auth.ldap.enable');

        $this->login = app('laravolt.auth.login');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth::login');
    }

    public function login(Request $request)
    {
        if ($this->ldapEnabled) {
            try {
                return $this->ldapLogin($request);
            } catch (\Exception $e) {
                return $this->defaultLogin($request);
            }
        }

        return $this->defaultLogin($request);
    }

    protected function ldapLogin(Request $request)
    {
        $ldapService = app(LdapService::class);

        $ldapService->resolveUser($this->credentials($request));
        $user = $ldapService->eloquentUser();

        if ($user && auth()->login($user)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        return config('laravolt.auth.identifier');
    }

    protected function validateLogin(Request $request)
    {
        $rules = $this->login->rules($request);
        $this->validate($request, $rules);
    }

    protected function credentials(Request $request)
    {
        return $this->login->credentials($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (method_exists($this->login, 'authenticated')) {
            return $this->login->authenticated($request, $user);
        }
    }

    /**
     * The user has been logged out
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        if (method_exists($this->login, 'loggedOut')) {
            return $this->login->loggedOut($request);
        }
    }
}
