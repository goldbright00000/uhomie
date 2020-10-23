<?php

namespace App\Http\Middleware;
use Closure;

class ApiDataLogger
{
    private $startTime;/**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next){
        $this->startTime = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response)
    {
        if ( env('API_DATALOGGER', true) ) {
            $endTime = microtime(true);
            $filename = 'api_datalogger_' . date('d-m-y') . '.log';
            $dataToLog  = '['   . gmdate("Y-m-d  G:i:s") .']'. "\n";
            $dataToLog .= ' Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= ' IP Address: ' . $request->ip() . "\n";
            $dataToLog .=  'IP HTTP_X_FORWARDED_FOR: ' . $this->getRealIpAddr() . "\n";
            $dataToLog .= ' URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= ' Method: ' . $request->method() . "\n";
            $dataToLog .= ' Input: '  . $request->getContent() . "\n";
            $dataToLog .= ' Output: ' . $response->getContent() . "\n";
            \File::append( storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n");

        } 
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