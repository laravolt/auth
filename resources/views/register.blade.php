@extends(config('laravolt.auth.layout'))

@section('content')

    <form class="ui form" method="POST" action="{{ route('auth::register') }}">
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
        <button type="submit" class="ui button fluid primary">@lang('auth::auth.register')</button>
    </form>

    <div class="ui divider hidden section"></div>
    @lang('auth::auth.already_registered?') <a href="{{ route('auth::login') }}">@lang('auth::auth.login_here')</a>

@endsection
