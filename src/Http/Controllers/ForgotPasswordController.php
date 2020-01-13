<?php

namespace Laravolt\Auth\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth::forgot');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, app('laravolt.auth.password.forgot')->rules());

        $identifierColumn = config('laravolt.auth.password.forgot.identifier') ?? config('laravolt.auth.identifier');
        $user = app('laravolt.auth.password.forgot')->getUserByIdentifier($request->get($identifierColumn));

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $response = Password::INVALID_USER;
        if ($user) {
            $response = app('password')->sendResetLink($user);
        }

        if ($response === Password::RESET_LINK_SENT) {
            $email = $user->getEmailForPasswordReset();

            return back()->withSuccess(trans($response, ['email' => $email, 'emailMasked' => $this->maskEmail($email)]));
        }

        // If an error was returned by the password broker, we will get this message
        // translated so we can notify a user of the problem. We'll redirect back
        // to where the users came from so they can attempt this process again.
        return back()->withErrors(
            ['email' => trans($response)]
        );
    }

    protected function mask($str, $first, $last)
    {
        $len = strlen($str);
        $toShow = $first + $last;

        return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*",
                $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }

    protected function maskEmail($email)
    {
        $mail_parts = explode("@", $email);
        $domain_parts = $mail_parts[1];

        $mail_parts[0] = mask($mail_parts[0], 3, 2); // show first 3 letters and last 2 letter
        $domain_parts = mask($domain_parts, 3, 2);
        $mail_parts[1] = $domain_parts;

        return implode("@", $mail_parts);
    }
}
