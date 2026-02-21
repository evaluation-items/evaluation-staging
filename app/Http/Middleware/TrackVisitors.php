<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Prevent duplicate entry per session
        if (!session()->has('visitor_logged')) {
            Visitor::create([
                'ip' => $request->ip(),
            ]);
            session(['visitor_logged' => true]);
        }
        return $next($request);
    }
}
