<?php

namespace App\Http\Middleware;

use Closure;
use App\Membership;
class CheckAgentMemberships
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
        if (!Membership::checkAgentMemberships()) {
          return redirect('/users/agent/memberships');
        }
        return $next($request);
    }
}
