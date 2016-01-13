<?php

namespace Laravolt\Auth\Http\Controllers;

use Validator;
use App\Entities\User;
use Illuminate\Http\Request;
use Laravolt\Auth\Activation;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $redirectAfterLogin = '/';
    protected $redirectAfterLogout = '/';
    protected $loginPath = '/auth/login';
    protected $loginUsername = 'email';

    use AuthenticatesUsers, RegistersUsers, Activation, ThrottlesLogins, ValidatesRequests {
        Activation::postRegister insteadof RegistersUsers;
        AuthenticatesUsers::getGuard insteadof RegistersUsers;
    }

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function redirectpath()
    {
        return url('/');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin(Request $request)
    {
        request()->session()->flash('next', $request->get('next'));

        return view('auth::auth.login');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth::auth.register');
    }

    protected function authenticated(Request $request, $user)
    {
        if($request->session()->has('next')) {
            return redirect($request->session()->get('next'));
        }
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only($this->loginUsername(), 'password') + ['status' => 'ACTIVE'];
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return trans('auth::auth.failed');
    }

}
