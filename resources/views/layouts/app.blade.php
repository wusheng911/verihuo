<!DOCTYPE html>
<html style="height:100%;" lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="baidu-site-verification" content="mRJiU1v8SN" />
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <meta name="applicable-device" content="pc">
    <link href="/favicon.ico" type="image/x-icon" rel=icon>
    <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon">

    <!-- Styles Fonts -->
    <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/font-awesome/css/font-awesome.min.css">
    <!-- Styles -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" media="all" type="text/css" rel="stylesheet">

    <link href="{{ asset('assets/vendor/amaze/css/amazeui.min.css') }}" media="all" type="text/css" rel="stylesheet">

    <link href="{{ asset('assets/css/animate.css') }}" media="all" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/css/verihuo.css') }}" media="all" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/css/slice-box/slicebox.css') }}" media="all" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/css/slice-box/custom.css') }}" media="all" type="text/css" rel="stylesheet">
    @yield('styles')
    <script type="text/javascript" src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/vendor/jquery/plugin/jquery.lazyload.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/vendor/amaze/js/amazeui.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/vendor/flot/jquery.flot.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/js/jquery.backstretch.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>

</head>
<body style="min-height:100%;margin:0;padding:0;position:relative; background-color: #f7f7f7" id="app-layout">
    @yield('styles')
    @include('elements/frontend/popbottom')
    @include('elements/frontend/header')
    @yield('content')
    @include('elements/frontend/footer')
    @yield('scripts')
    <script defer type="text/javascript" src="{{ asset('assets/js/pc.login.js') }}"></script>
</body>
</html>
