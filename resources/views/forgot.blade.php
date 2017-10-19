@extends(config('laravolt.auth.layout'))

@section('content')

    @if (session('status'))
        <?php flash()->success(session('status')); ?>
    @endif

    <form class="ui form" method="POST" action="{{ route('auth::forgot') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="ui field left icon input fluid">
            <input type="email" name="email" placeholder="@lang('auth::auth.email')" value="{{ old('email') }}">
            <i class="mail icon"></i>
        </div>
        <button type="submit" class="ui fluid button primary">@lang('auth::auth.send_reset_password_link')</button>
    </form>

    @if(config('laravolt.auth.registration.enable'))
        <div class="ui divider hidden section"></div>
        @lang('auth::auth.not_registered_yet?') <a href="{{ route('auth::register') }}">@lang('auth::auth.register_here')</a>
    @endif
@endsection
