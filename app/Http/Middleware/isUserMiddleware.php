<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isUserMiddleware
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
        if( Auth::check() && Auth::user()->role == 1){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 2){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 3){
            return $next($request);
        }else if( Auth::check() && Auth::user()->role == 4){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 5){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 6){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 7){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 8){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 9){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 10){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 11){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 12){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 13){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role == 14){
            return $next($request);
        }
        elseif( Auth::check() && Auth::user()->role >=  22){
            return $next($request);
        }
        else{
            return redirect()->route('login');
        }
    }
}
