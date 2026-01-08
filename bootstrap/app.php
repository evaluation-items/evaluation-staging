<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);
     
        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'isAdmin' => \App\Http\Middleware\isAdminMiddleware::class,
            'isUser' => \App\Http\Middleware\isUserMiddleware::class,
            'isImplementationMiddleware' => \App\Http\Middleware\isImplementationMiddleware::class,
            'DeptsecMiddleware' => \App\Http\Middleware\DeptsecMiddleware::class,
            'DeptdsMiddleware' => \App\Http\Middleware\DeptdsMiddleware::class,
            'DeptusMiddleware' => \App\Http\Middleware\DeptusMiddleware::class,
            'GadsecMiddleware' => \App\Http\Middleware\GadsecMiddleware::class,
            'EvaldirMiddleware' => \App\Http\Middleware\EvaldirMiddleware::class,
            'EvaljointdirMiddleware' => \App\Http\Middleware\EvaljointdirMiddleware::class,
            'EvalddMiddleware' => \App\Http\Middleware\EvalddMiddleware::class,
            'EvalroMiddleware' => \App\Http\Middleware\EvalroMiddleware::class,
            'GaddsMiddleware' => \App\Http\Middleware\GaddsMiddleware::class,
            'GadusMiddleware' => \App\Http\Middleware\GadusMiddleware::class,
            'PreventBackHistory' => \App\Http\Middleware\PreventBackHistory::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
