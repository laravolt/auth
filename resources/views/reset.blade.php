@extends(config('laravolt.auth.layout'))

@section('content')

    @if (session('status'))
        <?php flash()->success(session('status')); ?>
    @endif

    <form class="ui form" method="POST" action="{{ route('auth::reset', $token) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="ui field fluid">
            <input type="email" name="email" placeholder="@lang('auth::auth.email')" value="{{ old('email', $email) }}">
        </div>
        <div class="ui field fluid">
            <input type="password" name="password" placeholder="@lang('auth::auth.password_new')">
        </div>
        <div class="ui field fluid">
            <input type="password" name="password_confirmation" placeholder="@lang('auth::auth.password_confirm')">
        </div>
        <button type="submit" class="ui fluid button primary">@lang('auth::auth.reset_password')</button>
    </form>

    <div class="ui divider hidden section"></div>
    @lang('auth::auth.already_registered?') <a href="{{ route('auth::login') }}">@lang('auth::auth.login_here')</a>
@endsection
