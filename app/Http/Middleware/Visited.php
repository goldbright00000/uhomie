<?php

namespace App\Http\Middleware;

use Closure;
use App\{Property, Visit};
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;
use function GuzzleHttp\json_encode;

class Visited
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
        
        $request->route('id');
        $property = Property::find($request->route('id'));
        if($property) {
            $property->views = $property->views + 1;
            $property->save();
            $visit = new Visit;
            $visit->property_id = $property->id;
            $visit->save();
            if( $user = \Auth::user() ){
                try
                {
                    $client = new Client(env('RECOMBEE_PROD_ID_DATABASE', 'uhomy-prod'), env('RECOMBEE_PROD_PRIVATE_TOKEN', 'g5cvt1drVG6ULsopNvfuf5P6UEQXnQuU7EKUkc4O7VENS9LEdqv6BIyAMp4QyK8b'));
                    $r = new Reqs\AddDetailView("user_{$user->document_number}", "property_{$property->id}", ['cascadeCreate' => true]);
                    //$request->setTimeout(10000);
                    $client->send( $r );
                    return $next($request);
                }
                catch(Ex\ApiTimeoutException $e)
                {
                    //Handle timeout => use fallback
                    return $next($request);
                }
                catch(Ex\ResponseException $e)
                {
                    //Handle errorneous request => use fallback
                    return $next($request);
                }
                catch(Ex\ApiException $e)
                {
                    //ApiException is parent of both ResponseException and ApiTimeoutException
                    return $next($request);
                }
                
            } else {
                try
                {
                    $client = new Client(env('RECOMBEE_PROD_ID_DATABASE', 'uhomy-prod'), env('RECOMBEE_PROD_PRIVATE_TOKEN', 'g5cvt1drVG6ULsopNvfuf5P6UEQXnQuU7EKUkc4O7VENS9LEdqv6BIyAMp4QyK8b'));
                    $r = new Reqs\AddDetailView("user_guest_".$request->session()->get('_token'), "property_{$property->id}", ['cascadeCreate' => true]);
                    //$request->setTimeout(10000);
                    $client->send( $r );
                    return $next($request);
                }
                catch(Ex\ApiTimeoutException $e)
                {
                    //Handle timeout => use fallback
                    return $next($request);
                }
                catch(Ex\ResponseException $e)
                {
                    //Handle errorneous request => use fallback
                    return $next($request);
                }
                catch(Ex\ApiException $e)
                {
                    //ApiException is parent of both ResponseException and ApiTimeoutException
                    return $next($request);
                }
                catch(\Exception $e){
                    return $next($request);
                }
            }
            $ubicacion = $property->city()->first()->name;
            $precio = $property->rent;
            $cantidad_habitaciones = $property->bedrooms;
            $client->send(new SetItemValues( "property_{$property->id}" , ['ubicacion' => $ubicacion, 'precio' => $precio, 'habitaciones' => $cantidad_habitaciones], [ //optional parameters:
                'cascadeCreate' => true
              ]));
                
        }
        return $next($request);
    }
}
