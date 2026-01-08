<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Referrer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
		$path = $request->path();
		$route = $request->route()->getName();
		
		if(!request()->headers->get('referer')){
			abort(403);
		}
		
        return $next($request);
    }
}
