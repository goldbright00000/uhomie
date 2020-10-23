<?php

namespace App\Http\Middleware;

use Closure;

class RegistrationCheck
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
        $route_name = explode(".", \Request::route()->getName());
        if (\Auth::user()->{$route_name[1].'_profile_redirect'}) {

            $route = \Auth::user()->{$route_name[1].'_profile_redirect'};
            return redirect()->route($route);
        }
        
        return $next($request);
    }
}
