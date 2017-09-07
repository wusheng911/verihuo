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
        <meta name="description" content="@yield('description')">
        <meta name="keywords" content="@yield('keywords')">
        <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml"/>
        @yield('meta')

        <title>@yield('title')</title>

        <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/app.css" />
        @yield('css-top')

        <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/modernizr.js"></script>
        @yield('script-top')
        
        @if (getenv('APP_ENV') == 'production' && config('settings.baidu_tongji_islive', 0))
            <script>
             // baidu tongji
             var _hmt = _hmt || [];
             (function() {
                 var hm = document.createElement("script");
                 hm.src = "//hm.baidu.com/hm.js?a940ef628ff2e633c2d1c25556f19aa3";
                 var s = document.getElementsByTagName("script")[0]; 
                 s.parentNode.insertBefore(hm, s);
             })();
            </script>           
        @endif       
    </head>
    <body>
        @if(Auth::check())
            <input type="hidden" name="global_user_name" value="{{Auth::user()->getUserName()}}"/>
            <input type="hidden" name="global_user_id" value="{{Auth::user()->id}}"/>
        @else       
            <input type="hidden" name="global_user_name" value=""/>
            <input type="hidden" name="global_user_id" value=""/>      
        @endif
        
        <?php
            if (Helpers::getPlatform() == 'shop') {
                $is_shop = true;
            } elseif(is_my()) {
                $is_shop = true;
            }
        ?>
                
        <div id="menu-block" class="off-canvas-wrap" data-offcanvas>
          <div class="inner-wrap">
              {{-- top header --}}
              @include('mobile.elements.header', ['is_shop' => isset($is_shop)?$is_shop:false]) 
              {{-- menu --}}
              @if(isset($is_shop) && $is_shop)
                @include('mobile.elements.shopmenu')
              @else
                @include('mobile.elements.offcanvas') 
              @endif
              {{-- 导航 --}}
              @yield('navigatorTop')
              <main class="{{isset($subnav) && $subnav ? "chm-main-section-withnav":"chm-main-section"}} 
                    {{isset($is_shop) && $is_shop?"chm-shop":""}}" 
                    id="main-section" 
                    style="{{(isset($no_scroll) && $no_scroll)?"overflow-y: hidden !important;":""}}
                    {{isset($has_nav_bottom) && $has_nav_bottom?"":"padding-bottom:0 !important;"}}">
                  {{-- main content --}}
                  @yield('content')
                  @if(isset($has_nav_bottom) && $has_nav_bottom)
                      <div class="row columns">
                          <div class="small-12 columns" style="height:2.6rem;">&nbsp;</div>
                      </div>   
                  @endif
                  @include('mobile.elements.modalAlert')
                  @include('mobile.elements.modalFadeAlert')
              </main>
              {{-- bottom navigator --}}
              @yield('navigatorBottom')
              <a class="exit-off-canvas"></a>
          </div>           
        </div> 
        
        <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/jquery.js"></script>
        <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/jquery.cookie.js"></script>
        <script type="text/javascript" src="/assets/vendor/foundation/js/foundation.min.js"></script>
        <script type="text/javascript" src="/assets/vendor/foundation/js/foundation.offcanvas.js"></script>
        <script type="text/javascript" src="/assets/js/utils.js"></script>
        <script type="text/javascript" src="/assets/js/mobile.common.js"></script>
        @if(isset($is_shop) && $is_shop)
            <script type="text/javascript" src="/assets/js/shop.common.js"></script>
            <script type="text/javascript" src="/assets/js/mobile.shop.js"></script>
        @endif
        @yield('script-file-bottom')  
        <!-- cnzz -->
        @if (getenv('APP_ENV') == 'production' && config('settings.cnzz_tongji_islive', 0))
            <div style="display:none;">
                <script src="http://s11.cnzz.com/z_stat.php?id=1258099738&web_id=1258099738" language="JavaScript"></script>
            </div>        
        @endif
    </body>
</html>
