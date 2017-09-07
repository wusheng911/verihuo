<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\Helpers;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check()) {
            switch($guard){
              case 'admin':
                  redirect('/' . Config('chaohun.admin_prefix'));
                  break;
              case 'web':
                  redirect(Helpers::getHomeUrl(Helpers::getPlatform()));
                  break;
              default:
                  redirect(Helpers::getHomeUrl(Helpers::getPlatform()));
            }
        }

        return $next($request);
    }
}
