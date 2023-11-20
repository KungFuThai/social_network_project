<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
</head>
<body>
@if(auth()->check())
    <script>
        window.Laravel = {!! json_encode([
            'isLoggedIn' => true,
            'user'       => auth()->user(),
        ], JSON_THROW_ON_ERROR) !!}
    </script>
@else
    <script>
        window.Laravel = {!! json_encode([
            'isLoggedIn' => false,
        ], JSON_THROW_ON_ERROR) !!}
    </script>
@endif
    <div id="app"></div>
    <script src="http://localhost:6001/socket.io/socket.io.js"></script>
    @vite('resources/js/app.js')
</body>
</html>
