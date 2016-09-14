@if(config('laravolt.auth.services'))

    <div class="ui icon buttons basic">
        @foreach(config('laravolt.auth.services') as $service)
            <a href="{{ route('auth::social.login', $service) }}" class="ui icon button">
                <i class="{{ $service }} icon"></i>
            </a>
        @endforeach
    </div>

    <div class="ui divider section horizontal">@lang('auth::auth.or')</div>
@endif
