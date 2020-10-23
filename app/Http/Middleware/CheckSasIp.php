<?php

namespace App\Http\Middleware;

use Closure;

class CheckSasIp
{
    public $whiteIps; // Ips permitidas from .env

    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */

    public function handle($request, Closure $next)

    {
        $this->whiteIps = explode(';', env('SAS_IPS_ARRAY')); // Traigo las ip permitidas de .env
        $arreglo_ips_incoming = explode(',',$this->getRealIpAddr()); // Poblo array con ips incoming
        $flag = false;
        if( in_array('*', $this->whiteIps) ){ // Si hay * se permiten todas las ips
            return $next($request);
        } 
        foreach( $arreglo_ips_incoming as $ip  ){
            if ( in_array($ip, $this->whiteIps)  ) { // verifico que ips incomings esten en el arreglo de ips permitidas
                return $next($request);
            }
        }
        if( !$flag ){
            return response()->json([
                'status' => 'error',
                'description' => 'Tu ip no es valida ('.$this->getRealIpAddr().')',
                'code' => '401'
            ]);
        }
        return $next($request);
    }

    private function getRealIpAddr(){
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
            {
                $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                $ip=$_SERVER['REMOTE_ADDR'];
            }
            return $ip;
    }

}