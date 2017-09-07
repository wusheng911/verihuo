<!DOCTYPE html>
<html lang="zh-CN">
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
    <link href="{{ asset('assets/css/main.css') }}" media="all" type="text/css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="/assets/css/pc.css" /> -->
    @yield('styles')
    <script type="text/javascript" src="{{ asset('assets/libs/jquery-1.12.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/jquery/plugin/jquery.lazyload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>

</head>
<body style="background-color: #f7f7f7" id="app-layout">

    @if(Auth::check())
        <input type="hidden" name="global_user_name" value="{{Auth::user()->getUserName()}}"/>
        <input type="hidden" name="global_user_id" value="{{Auth::user()->id}}"/>
    @else       
        <input type="hidden" name="global_user_name" value=""/>
        <input type="hidden" name="global_user_id" value=""/>      
    @endif
    
    @include('elements/frontend/popbottom')
    @include('elements/frontend/shopheader')
    @include('elements/frontend/shopnavigator')
	@include('elements.shopSideBar')
    @yield('content')
    @include('elements/frontend/footer')
    @include('pc.elements.modalFadeAlert')

    <!-- JavaScripts -->
    <div style="display:none;">

    <script src="/assets/libs/bootstrap-daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
    <!--
    <script type = text/javascript src="/assets/js/nav.js"></script>
    -->
    @if(isset($is_shop) && $is_shop)
        <script type="text/javascript" src="/assets/js/shop.common.js"></script>
        <script type="text/javascript" src="/assets/js/pc.shop.js"></script>
    @endif   
    @yield('scripts')
    <!-- cnzz -->
    @if (getenv('APP_ENV') == 'production' && config('settings.cnzz_tongji_islive', 0))
    <script src="http://s11.cnzz.com/z_stat.php?id=1258099738&web_id=1258099738" language="JavaScript"></script>
    @endif
    <!-- EC Lite在线客服 -->
    @if (getenv('APP_ENV') == 'production')
        <script type="text/javascript" src="http://cs.ecqun.com/?id=1678907" charset="utf-8"></script>
        <script>
    @endif
    
    getNavData = "{{ action('HomeController@getnavdata') }}";
    newsPath = "/news";
    tagPath = "{{ action('ViewController@tagSearchForPc',null) }}";
    $(document).ready(function(){
         $("img.lazy").lazyload({
             effect : "fadeIn",
             threshold : 200
         });
    });
    </script>
    </div>

</body>
</html>
