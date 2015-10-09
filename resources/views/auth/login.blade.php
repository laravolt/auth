@extends('layouts.auth')

@section('content')

    @if (session('success'))
        <div class="ui success message">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="ui segment very padded">

        <div class="two ui buttons basic">
            <a href="{{ url('auth/facebook/login') }}" class="ui button">
                <i class="facebook icon"></i>
                Facebook
            </a>
            <a href="{{ url('auth/github/login') }}" class="ui button">
                <i class="github icon"></i>
                Github
            </a>
        </div>
        <div class="ui divider section horizontal">Atau</div>

        <form class="ui form large" method="POST" action="{{ url('/auth/login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="ui field left icon input fluid">
                <input type="email" name="email" placeholder="@lang('auth::auth.identifier')" value="{{ old('email') }}">
                <i class="mail icon"></i>
            </div>
            <div class="ui field left icon input fluid">
                <input type="password" name="password" placeholder="@lang('auth::auth.password')">
                <i class="lock icon"></i>
            </div>
            <div class="ui field">
                <button type="submit" class="ui big fluid button">@lang('auth::auth.login')</button>
            </div>
            <div class="ui equal width grid field">
                <div class="column left aligned">
                    <div class="ui checkbox big">
                        <input type="checkbox" name="remember" {{ request()->old('remember')?'checked':'' }}>
                        <label>@lang('auth::auth.remember')</label>
                    </div>
                </div>
                <div class="column right aligned">
                    <a href="{{ url('/password/email') }}">@lang('auth::auth.forgot_password')</a>
                </div>
            </div>

        </form>

    </div>

    <div class="ui list small">
        <div class="item">
            @lang('auth::auth.not_registered_yet?')
            <a href="{{ url('auth/register') }}">@lang('auth::auth.register_here')</a>
        </div>
    </div>

@endsection
