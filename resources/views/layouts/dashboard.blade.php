<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" type="image/x-icon" href="/favicon.png"/>

        <!-- css -->
        <link rel="stylesheet" href="/static/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="/static/css/AdminLTE.min.css">
        <link rel="stylesheet" href="/static/css/skin-blue.min.css">
        <link rel="stylesheet" href="/static/js/plugins/pace/pace.min.css">

        <title>{{ config('app.name', 'Lumenous.org') }} - @yield('title')</title>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini">

        <div id="app">
            @yield('content')
        </div>

        <!-- built files will be auto injected. Static files below -->
        <script src="/static/js/plugins/jQuery/jQuery-2.2.0.min.js"></script>
        <script src="/static/js/plugins/bootstrap/bootstrap.min.js"></script>
        <script src="/static/js/plugins/AdminLTE/app.min.js"></script>
        <script src="/static/js/plugins/pace/pace.min.js"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
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
        @stack('scripts')
    </body>
</html>
