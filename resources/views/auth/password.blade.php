@extends('layouts.auth')

@section('content')
    @if (session('status'))
        <div class="ui positive message">
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <form class="ui form segment very padded" method="POST" action="{{ url('/password/email') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="ui field left icon input big fluid">
            <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}">
            <i class="mail icon"></i>
        </div>
        <button type="submit" class="ui big fluid button">Kirim Link Reset Password</button>
    </form>
    Belum punya akun? <a href="{{ url('auth/register') }}">Daftar Disini</a>

@endsection
