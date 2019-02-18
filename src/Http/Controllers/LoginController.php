<?php

namespace Laravolt\Auth\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    use AuthenticatesUsers, ValidatesRequests;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (method_exists($this->login, 'authenticated')) {
            return $this->login->authenticated($request, $user);
        }
    }
}
