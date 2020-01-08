<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Podcard.fm</title>

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@joshuaanderton" />
        <meta name="twitter:image" content="https://jads.s3-us-west-2.amazonaws.com/podcard/podcast-editing.png" />

        <meta property="og:type" content="url">
        <meta property="og:title" content="Podcast Editing Services">
        <meta property="og:description" content="Focus on the content, we'll take care of the rest." />
        <meta property="og:url" content="https://editing.podcard.fm">
        <meta property="og:image" content="https://jads.s3-us-west-2.amazonaws.com/podcard/podcast-editing.png">

        <script src="{{ asset('js/site.js') }}?v=4" defer></script>
        <link href="{{ asset('css/site.css') }}?v=4" rel="stylesheet">
        <link rel="shortcut icon" href="/favicon.png"/>

        <link href="https://fonts.googleapis.com/css?family=Barlow:400,600,700,900&display=swap" rel="stylesheet">

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-83304079-2"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-83304079-2');
        </script>

    </head>
    <body>
        @yield('content')
    </body>
</html>