<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use function Aws\serialize;

class LogMiddleware
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
        $response = $next($request);
        dd( serialize($request->headers) );
        Log::info('Dump request', [
            'request_headers' => serialize( $request->headers ),
            'response_headers' => serialize($response->headers),
            'response_status_code' => serialize($response->statusCode),
            'response_status_text' => serialize($response->statusText)
        ]);

        return $response;
    }
}
