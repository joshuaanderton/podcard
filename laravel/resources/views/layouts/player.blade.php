<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @include('head')
    @vite('resources/js/player.js')
  </head>
  <body>
    @yield('content')
  </body>
</html>