<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        if (env('FORCE_HTTPS', false)) {
            $middleware->append(\App\Http\Middleware\ForceHttps::class);
            $middleware->append(\App\Http\Middleware\ForceNonWww::class);
        }

        // Configurar redirección a dashboard Vue cuando el usuario ya está autenticado
        RedirectIfAuthenticated::redirectUsing(fn () => '/dashboard-vue');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();