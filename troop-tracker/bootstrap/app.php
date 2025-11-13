<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void
    {
        // // Global middleware (applied to all requests)
        // $middleware->append(App\Http\Middleware\FlashMessageMiddleware::class);
    
        // Middleware groups (e.g., 'web', 'api')
        $middleware->web(append: [
            \App\Http\Middleware\FlashMessageMiddleware::class,
            \App\Http\Middleware\HtmxDispatchHeaderMiddleware::class,
            // ... other web middleware
        ]);

        $middleware->redirectGuestsTo(fn(Illuminate\Http\Request $request) => route('auth.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void
    {
        //
    })->create();
