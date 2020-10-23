<?php

namespace App\Http\Middleware;

use Closure;
use App\{Configuration};

class VerificatedUser
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
        if (!\Auth::guard('web')->user()->mail_verified) {
            return redirect('/');
        } elseif (!\Auth::guard('web')->user()->phone_verified) {
            $configuration = Configuration::where('name', 'phone-verify')->first();
            if($configuration->enabled == 1){
                return redirect('users/phone-verify');
            }
        }
        return $next($request);
    }
}
