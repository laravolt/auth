<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authentication</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.6/semantic.css">
</head>
<body>

<div class="ui divider hidden"></div>
<div class="ui segment basic center aligned">
    <a href="{{ url('/') }}"><i class="icon home circular inverted teal"></i></a>
</div>
<div class="ui divider hidden"></div>

<div class="ui centered stackable grid">
    <div class="column seven wide center aligned">
        @include('auth::auth.error')
        @yield('content')
    </div>
</div>

</body>
</html>
