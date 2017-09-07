<?php

namespace App\Http\Middleware;

use Closure;
use EasyWeChat\Foundation\Application;
use Auth;
use Event;
use Overtrue\LaravelWechat\Events\WeChatUserAuthorized;
use App\Services\Helpers;

/**
 * Class OAuthAuthenticate.
 */
class WechatPlatformAuthenticate
{
    /**
     * Use Service Container would be much artisan.
     */
    private $wechat;

    /**
     * Inject the wechat service.
     */
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if the request not came from Micro Message platform, do nothing;
        if(!Helpers::isWechat()){
            return $next($request);
        }

        config(['session.lifetime' => config('wechat.session.lifetime', 60 * 60 * 24 * 30),
                'session.expire_on_close' => config('wechat.session.expire_on_close', false)]);
        if (Auth::check() && !$request->is('customer/login*')){
            return $next($request);
        }

        if (!session('wechat.oauth_user')) {

            $scopes = config('wechat.oauth.scopes', ['snsapi_base']);

            if (is_string($scopes)) {
                $scopes = array_map('trim', explode(',', $scopes));
            }

            //如果是/customer/login页面并且是微信登陆过跳转到个人中心
            if($request->is('customer/login*')){
                $backurl = 'customer/info';
            }else{
                $backurl = $request->fullUrl();
            }

            //return $this->wechat->oauth->scopes($scopes)->redirect($request->fullUrl());
            return $this->wechat->oauth->scopes($scopes)->setRequest($request)->redirect(url(config('wechat.oauth.callback').'?back_url='.$backurl));
        }else{
            //清空oauth user后重新尝试
            session(['wechat.oauth_user' => null]);
        }

        return $next($request);
    }

}
