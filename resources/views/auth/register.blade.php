@extends('layouts.auth')

@section('content')

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

        <form class="ui large form" method="POST" action="{{ url('/auth/register') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="ui field left icon input fluid">
                <input type="text" name="name" placeholder="@lang('auth::auth.name')" value="{{ old('name') }}">
                <i class="user icon"></i>
            </div>
            <div class="ui field left icon input fluid">
                <input type="email" name="email" placeholder="@lang('auth::auth.identifier')" value="{{ old('email') }}">
                <i class="mail icon"></i>
            </div>
            <div class="ui field left icon input fluid">
                <input type="password" name="password" placeholder="@lang('auth::auth.password')">
                <i class="lock icon"></i>
            </div>
            <button type="submit" class="ui big button fluid">@lang('auth::auth.register')</button>
        </form>
    </div>
    @lang('auth::auth.already_registered?') <a href="{{ url('auth/login') }}">@lang('auth::auth.login_here')</a>

@endsection
