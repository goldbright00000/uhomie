<?php

namespace App\Http\Middleware;

use Closure;
use App\{Membership};

class OwnerCheck
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
        if (!Membership::checkOwnerMemberships()) {
            abort(404);
        }/*elseif (\Request::route()->getName() != "properties.first-step") {
            if (\Auth::user()->properties()->where('id', $request->id)) {
                # code... TODO: Validations on here!
            }
        }*/
        return $next($request);
    }
}
