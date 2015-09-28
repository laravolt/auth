@extends('layouts.auth')

@section('content')

    @if (session('status'))
        <div class="ui positive message">
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <form class="ui large form segment very padded" method="POST" action="{{ url('/password/reset') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="ui field fluid">
            <input type="email" name="email" placeholder="@lang('auth::auth.email')" value="{{ old('email') }}">
        </div>
        <div class="ui field fluid">
            <input type="password" name="password" placeholder="@lang('auth::auth.password_new')">
        </div>
        <div class="ui field fluid">
            <input type="password" name="password_confirmation" placeholder="@lang('auth::auth.password_confirm')">
        </div>
        <button type="submit" class="ui big fluid button">@lang('auth::auth.reset_password')</button>
    </form>

    @lang('auth::auth.already_registered?') <a href="{{ url('auth/login') }}">@lang('auth::auth.login_here')</a>
@endsection
