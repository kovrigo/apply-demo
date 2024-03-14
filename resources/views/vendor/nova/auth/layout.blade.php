<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Laravel\Nova\Nova::name() }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('app.css', 'vendor/nova') }}">

    <!-- Custom Meta Data -->
    @include('nova::partials.meta')

    <!-- Theme Styles -->
    @foreach(\Laravel\Nova\Nova::themeStyles() as $publicPath)
        <link rel="stylesheet" href="{{ $publicPath }}">
    @endforeach
</head>
<body class="h-full login">
    <div class="h-full login-form">
        @yield('content')
    </div>
    <div class="login-decoration">
        <img class="logo" src="/storage/login-logo.svg">

        <svg class="rect" width="879" height="303" viewBox="0 0 879 303" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect y="326" width="647.846" height="633.122" transform="rotate(-30.2123 0 326)" fill="#111111"/>
        </svg>

        <svg class="circle" width="734" height="359" viewBox="0 0 734 359" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect y="-162.02" width="616.011" height="602.011" rx="301.005" transform="rotate(-30.2123 0 -162.02)" fill="#111111"/>
        </svg>

    </div>    
</body>
</html>
