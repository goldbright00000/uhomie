<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Sasapplicant;

class TokenSasVerification
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
        if(!$request->isJson()) return abort(403, 'Contenido incorrecto');

        $token = $request->route('token');
        //if($token == 'tokenprueba') return response()->json($request->input());
        $query = Sasapplicant::where('token', $token)->get();
        
        if ($query->count()){
            $registro = $query->first();
            
            $fecha_token = $registro->updated_at;
            
            $fecha_token->addHours(28);
            
            $ahora = Carbon::now();
            
            if( !$ahora->greaterThan($fecha_token) ){ // Si la fecha del token no es mayor a la de AHORA, el token expiró. (el token tendria duracion de 28 horas)
                return $next($request);
            } 
            return abort(403, 'Token Expirado');
            
        } else {
            return abort(403, 'Token incorrecto');
        }
    }
}
