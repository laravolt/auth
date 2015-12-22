<div class="ui icon buttons big basic">
    @foreach(config('laravolt-auth.services') as $service)
    <a href="{{ url('auth/' . $service . '/login') }}" class="ui button">
        <i class="{{ $service }} icon"></i>
    </a>
    @endforeach
</div>
