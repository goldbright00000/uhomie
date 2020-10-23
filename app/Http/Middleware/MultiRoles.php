<?php

namespace App\Http\Middleware;

use Closure;

class MultiRoles
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
        $user = \Auth::user();
        $role_route = explode(".", \Request::route()->getName());
        $role_route = $role_route[1];
        $authorized = false;
        foreach ($user->memberships as $m) {
            if ( $m->role->slug == $role_route ) {
                $authorized = true;
                break;
            }
        }
        if ( $authorized == false ) {
            return redirect("/");
        }
        return $next($request);
    }
}
