<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TeachAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $prefix = Config('constants.TEACHER_PREFIX');
        
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                if(!$request->is("{$prefix}/login*",
                                 "{$prefix}/register*",
                                 "{$prefix}/password/email*",
                                 "{$prefix}/password/reset*")){
                    return redirect()->guest("{$prefix}/login");
                }
            }
        }
        
        return $next($request);
    }
}