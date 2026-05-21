<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'Padel Booking') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-body auth-body--bg">
        <div class="auth-wrapper">
            <div class="auth-card auth-card--glass">
                @yield('content')
            </div>
        </div>
    </body>
</html>
