<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white dark:bg-black">
  <head>
    @include('head')
    @livewireStyles
  </head>
  <body class="text-black dark:text-white">
    @yield('content')
    {{ $slot }}
    @livewireScripts
  </body>
</html>