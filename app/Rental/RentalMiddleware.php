<?php

namespace App\Rental;

use Closure;

class RentalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if ($user = $request->user()){
            return $next($request);
        }
        

        // Get the current route.
        $route = $request->route();
        // Get the current route actions.
        $actions = $route->getAction();
        
        return abort(401,"unauthorize");
    }
}
