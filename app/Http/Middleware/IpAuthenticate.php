<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class IpAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //生产环境, 暂时不允许远程客户端调用api
        if (config('app.env') == 'production') {
            if ($request->server->get('REMOTE_ADDR') != "127.0.0.1")
            {
                Log::error("Client IP is:" . $request->server->get('REMOTE_ADDR'));
                return response()->json(['error' => '没有权限访问！'], 403);
            }        
        }
        
        return $next($request);
    }
}
