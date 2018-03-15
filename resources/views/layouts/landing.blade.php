<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Lumenous.org') }} - @yield('title')</title>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.png"/>
        <link rel="stylesheet" type="text/css" href="/css/app.css">
    </head>
    <body>
        <!--
        https://unsplash.com/photos/fcZcehwVMs4
        https://unsplash.com/photos/E0AHdsENmDg
        https://unsplash.com/photos/0o_GEzyargo
        https://unsplash.com/photos/Jztmx9yqjBw
        -->
        @include('shared.landing._header')

        <div id="app">
            @yield('content')
        </div>

        @include('shared.landing._footer')

        <script src="{{ asset('js/main.js') }}"></script>
        @stack('scripts')
        @if (!empty(env('GA_CODE')))
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
                // analytics section
                ga('create', '{{env('GA_CODE')}}', 'auto')
                ga('send', 'pageview')
            </script>
        @endif
    </body>
</html>