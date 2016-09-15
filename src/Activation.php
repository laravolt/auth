<?php
namespace Laravolt\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait Activation
{
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        $token = $this->createToken($user);

        Mail::send('auth::emails.activation', compact('token'), function($message) use ($user){
            $message->subject(trans('auth::auth.activation_subject'));
            $message->to($user['email']);
        });

        flash()->success(trans('auth::auth.registration_success'));
        return redirect()->back();
    }

    public function activate($token)
    {
        $userId = DB::table('users_activation')->whereToken($token)->pluck('user_id');

        if (!$userId) {
            abort(404);
        }

        $user = app(config('auth.providers.users.model'))->findOrFail($userId);
        $user->status = 'ACTIVE';
        $user->save();

        DB::table('users_activation')->whereToken($token)->delete();

        return redirect()->to($this->loginPath)->with('success', trans('auth::auth.activation_success'));
    }

    protected function createToken($user)
    {
        $token = md5(uniqid(rand(), true));
        DB::table('users_activation')->insert(['user_id' => $user->getKey(), 'token' => $token, 'created_at' => Carbon::now()]);

        return $token;
    }
}
