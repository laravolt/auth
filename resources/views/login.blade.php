@extends(config('laravolt.auth.layout'))

@section('content')

    @if (session('success'))
        <div class="ui success message">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="ui segment very padded">

        @include('auth::social')

        <form class="ui form" method="POST" action="{{ route('auth::login') }}">
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
                <button type="submit" class="ui fluid button primary">@lang('auth::auth.login')</button>
            </div>
            <div class="ui equal width grid field">
                <div class="column left aligned">
                    <div class="ui checkbox">
                        <input type="checkbox" name="remember" {{ request()->old('remember')?'checked':'' }}>
                        <label>@lang('auth::auth.remember')</label>
                    </div>
                </div>
                <div class="column right aligned">
                    <a href="{{ route('auth::forgot') }}">@lang('auth::auth.forgot_password')</a>
                </div>
            </div>

        </form>

    </div>

    @if(config('laravolt.auth.registration.enable'))
    <div class="ui list small">
        <div class="item">
            @lang('auth::auth.not_registered_yet?')
            <a href="{{ route('auth::register') }}">@lang('auth::auth.register_here')</a>
        </div>
    </div>
    @endif

@endsection
