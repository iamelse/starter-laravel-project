<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>{{ $title ?? 'Laravel' }}</title>

    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('mazer/assets/compiled/css/iconly.css') }}">

    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/favicon.png') }}" type="image/png">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <script src="{{ asset('mazer/assets/static/js/initTheme.js') }}"></script>
    
    <div id="app">

        @include('admin.layouts.partials.sidebar')
    
        <div id="main" class='layout-navbar navbar-fixed'>
            
            <header>
                @include('admin.layouts.partials.navbar')
            </header>
    
            @yield('content')     
    
            @include('admin.layouts.partials.footer')
            
        </div>
    </div>

    <script src="{{ asset('mazer/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    
    
    <script src="{{ asset('mazer/assets/compiled/js/app.js') }}"></script>
    
    @stack('scripts')
</body>

</html>