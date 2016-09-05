@extends(config('laravolt.auth.layout'))

@section('content')

    @if (session('status'))
        <div class="ui positive message">
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <form class="ui form segment very padded" method="POST" action="{{ route('auth::forgot') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="ui field left icon input fluid">
            <input type="email" name="email" placeholder="@lang('auth::auth.email')" value="{{ old('email') }}">
            <i class="mail icon"></i>
        </div>
        <button type="submit" class="ui fluid button primary">@lang('auth::auth.send_reset_password_link')</button>
    </form>
    @lang('auth::auth.not_registered_yet?') <a href="{{ route('auth::register') }}">@lang('auth::auth.register_here')</a>

@endsection
