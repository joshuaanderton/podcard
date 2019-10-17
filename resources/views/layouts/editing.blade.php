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

        <!-- Start of Async Drift Code -->
        <script>
        "use strict";

        !function() {
          var t = window.driftt = window.drift = window.driftt || [];
          if (!t.init) {
            if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
            t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ],
            t.factory = function(e) {
              return function() {
                var n = Array.prototype.slice.call(arguments);
                return n.unshift(e), t.push(n), t;
              };
            }, t.methods.forEach(function(e) {
              t[e] = t.factory(e);
            }), t.load = function(t) {
              var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
              o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
              var i = document.getElementsByTagName("script")[0];
              i.parentNode.insertBefore(o, i);
            };
          }
        }();
        drift.SNIPPET_VERSION = '0.3.1';
        drift.load('knezbhmtc5w9');
        </script>
        <!-- place this script tag after the Drift embed tag -->
        <script>
        (function() {
          /* Add this class to any elements you want to use to open Drift.
           *
           * Examples:
           * - <a class="drift-open-chat">Questions? We're here to help!</a>
           * - <button class="drift-open-chat">Chat now!</button>
           *
           * You can have any additional classes on those elements that you
           * would ilke.
           */
          var DRIFT_CHAT_SELECTOR = '.drift-open-chat'
          /* http://youmightnotneedjquery.com/#ready */
          function ready(fn) {
            if (document.readyState != 'loading') {
              fn();
            } else if (document.addEventListener) {
              document.addEventListener('DOMContentLoaded', fn);
            } else {
              document.attachEvent('onreadystatechange', function() {
                if (document.readyState != 'loading')
                  fn();
              });
            }
          }
          /* http://youmightnotneedjquery.com/#each */
          function forEachElement(selector, fn) {
            var elements = document.querySelectorAll(selector);
            for (var i = 0; i < elements.length; i++)
              fn(elements[i], i);
          }
          function openSidebar(driftApi, event) {
            event.preventDefault();
            driftApi.sidebar.open();
            return false;
          }
          ready(function() {
            drift.on('ready', function(api) {
              var handleClick = openSidebar.bind(this, api)
              forEachElement(DRIFT_CHAT_SELECTOR, function(el) {
                el.addEventListener('click', handleClick);
              });
            });
          });
        })();
        </script>
        <!-- End of Async Drift Code -->

    </head>
    <body>
        @yield('content')
    </body>
</html>
