<!DOCTYPE html>
<html>
<head>
    <title>@yield('site.title', "Welcome Home") | {{ config('app.name') }}</title>

    <meta charset="UTF-8"/>
    <meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <link rel="stylesheet" type="text/css" href="{{ asset('laravolt/semantic/semantic.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('laravolt/css/all.css') }}"/>

    @stack('style')

</head>

<body class="layout--auth">

<div class="ui divider hidden section"></div>
<div class="ui divider hidden section"></div>

<div class="ui centered stackable grid">
    <div class="column six wide center aligned">
        <h1 class="ui header top attached block">{{ config('app.name') }}</h1>

        <div class="ui segment very padded bottom attached">
            @include('auth::error')
            @yield('content')
        </div>
    </div>
</div>


<script type="text/javascript" src="{{ asset('laravolt/js/all.js') }}"></script>

@stack('script')

</body>
</html>
