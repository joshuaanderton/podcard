<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['h-full', 'dark' => !$is_light])>
  <head>
    @include('head')
    @vite('resources/js/player.js')
    @if (($font['name'] ?? null) && ($font['import'] ?? null))
      <link href="{{ $font['import'] }}" rel="stylesheet">
      <style>html { font-family: '{{ $font['name'] }}', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', 'Calibri Light', Roboto, sans-serif !important; }</style>
    @endif
    <style>[v-cloak] { display: none !important; }</style>
  </head>
  <body class="font-sans antialiased h-full" style="{{ implode('; ', $themeStyles) }}">
    @yield('content')
  </body>
</html>
