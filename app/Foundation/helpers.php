<?php

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

if (! function_exists('is_wechat')) {
    /**
     *
     */
    function is_wechat() {
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }	
        return false;
    }

}
if (! function_exists('is_my')) {
    /**
     *判断是否个人中心（移动端）
     */
    function is_my() {
        if (app()->request->is('my*')) {
            return true;
        }
        return false;
    }

}

if (! function_exists('s_g')) {
    /**
     * score -> grade
     */
    function s_g($s) {
        if($s >= 97) return "A+";
        if($s >= 94) return "A";
        if($s >= 90) return "A-";
        if($s >= 87) return "B+";
        if($s >= 84) return "B";
        if($s >= 80) return "B-";
        if($s >= 77) return "C+";
        if($s >= 74) return "C";
        if($s >= 70) return "C-";
        if($s >= 67) return "D+";
        if($s >= 64) return "D";
        if($s >= 60) return "D-";
        return "F";
    }
}

if (! function_exists('g_s')) {
    /**
     * score -> grade
     */
    function g_s($g) {
        $g = strtoupper($g);
        if($g == 'A+') return 98;
        if($g == 'A') return 95;
        if($g == 'A-') return 92;
        if($g == 'B+') return 88;
        if($g == 'B') return 85;
        if($g == 'B-') return 82;
        if($g == 'C+') return 78;
        if($g == 'C') return 75;
        if($g == 'C-') return 72;
        if($g == 'D+') return 68;
        if($g == 'D') return 65;
        if($g == 'D-') return 62;
        if($g == 'F') return 0;
    }
}