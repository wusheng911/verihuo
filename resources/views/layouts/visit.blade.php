<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="baidu-site-verification" content="CbCwNMXIxH"><!-- To ensure proper rendering and touch zooming-->
        <meta name="robots" content="index,follow"/>
        <meta name="applicable-device" content="mobile">
        <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml"/>
        @yield('meta')

        <title>@yield('title')</title>

        <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/app.css?v=201704181750" />
        @yield('css-top')

        <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/jquery.js"></script>
        <script src="/assets/js/app.js"></script>
        <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/modernizr.js"></script>
        @yield('script-top')
        
    </head>
    <body>
        @yield('content')
        <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/jquery.cookie.js"></script>
        <script type="text/javascript" src="/assets/vendor/foundation/js/foundation.min.js"></script>
        <script type="text/javascript" src="/assets/vendor/foundation/js/foundation.offcanvas.js"></script>
        <script type="text/javascript" src="/assets/js/utils.js"></script>
        @yield('script-file-bottom')  
        <script>       
         (function (doc, win) {   
             var docEl = doc.documentElement,   
                 resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',   
                 recalc = function () {   
                     var clientWidth = docEl.clientWidth;   
                     if (!clientWidth) return;   
                     docEl.style.fontSize = 16 * (clientWidth / 640) + 'px';   
                 };   
             if (!doc.addEventListener) return;   
             win.addEventListener(resizeEvt, recalc, false);   
             doc.addEventListener('DOMContentLoaded', recalc, false);   
         })(document, window);   
        </script>

    </body>
</html>
