<?php

namespace App\Http\Middleware;

use Closure;
use App\Membership;
class CheckServiceMemberships
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
        if (!Membership::checkServiceMemberships()) {
          return redirect('/users/service/memberships');
        }
        return $next($request);
    }
}
