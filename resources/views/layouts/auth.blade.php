<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <div class="container mx-auto h-screen flex">
        <div class="w-full max-w-md m-auto">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
