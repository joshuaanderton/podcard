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
    @if ($salestreamTeam = env('SALESTREAM_TEAM'))
      <script type="text/javascript">window.$salestream=[];(function(){d=document;s=d.createElement("script");s.src="https://salestream.app/{{ $salestreamTeam }}/embed.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
    @endif
  </body>
</html>