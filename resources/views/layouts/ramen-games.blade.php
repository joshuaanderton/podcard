<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>The Ramen Games - {{ config('app.name', 'Podcard.co') }}</title>
        <link rel="shortcut icon" href="/favicon.png"/>

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@gettingtoramen" />
        <meta name="twitter:image" content="https://ramengames.podcard.co/ramen-games-social.png" />

        <meta property="og:type" content="url">
        <meta property="og:title" content="The Ramen Games (by {{ config('app.name', 'Podcard.co') }})">
        <meta property="og:description" content="A list of podcasts following bootstrappers and their journeys to ramen profitable" />
        <meta property="og:url" content="https://podcard.co">
        <meta property="og:image" content="https://ramengames.podcard.co/ramen-games-social.png">

        @blazervelHead('resources/js/app.js')

        <link href="https://fonts.googleapis.com/css?family=Barlow:400,600,700,900&display=swap" rel="stylesheet">

        @if($site_id = env('FATHOM_SITE_ID'))
            <script defer src="https://cdn.usefathom.com/script.js" site="{{ $site_id }}"></script>
        @endif
    </head>
    <body class="bg-body-gradient">
        @yield('content')
    </body>
</html>
