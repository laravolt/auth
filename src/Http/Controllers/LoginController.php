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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

        $this->redirectTo = config('laravolt.auth.redirect.after_login');
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

    protected function validateLogin(Request $request)
    {
        if (version_compare(app()->version(), '5.3.0', '>=')) {
            $loginField = $this->username();
        } else {
            $loginField = $this->loginUsername();
        }

        $rules = [
            $loginField => 'required',
            'password'  => 'required',
        ];

        if (config('laravolt.auth.captcha')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $this->validate($request, $rules);
    }
}
