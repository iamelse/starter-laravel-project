<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>{{ $title ?? '' }}</title>

    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/auth.css') }}">
    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/favicon.png') }}" type="image/png">
</head>

<body>
    <script src="{{ asset('mazer/assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        @yield('content')
    </div>
</body>

</html>