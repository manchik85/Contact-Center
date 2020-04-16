<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="mask-icon" href="{{ asset('img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">

    <!-- Styles -->
    <link href="{{ asset('css/vendors.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.core.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body style="overflow-x: hidden">
    <div id="app" class="blankpage-form-field">
        @yield('content')
    </div>
    <video poster="{{ asset('img/backgrounds/clouds.png') }}" id="bgvid" playsinline autoplay muted loop>
        <source src="{{ asset('media/video/cc.webm') }}" type="video/webm">
        <source src={{ asset('media/video/cc.mp4') }}""  type="video/mp4">
    </video>
    <!-- Scripts -->
    @yield('js')
</body>
</html>
