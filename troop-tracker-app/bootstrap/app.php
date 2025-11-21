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
        $middleware->web(append: [
            \App\Http\Middleware\FlashMessageMiddleware::class,
            \App\Http\Middleware\HtmxDispatchHeaderMiddleware::class,
            \App\Http\Middleware\UpdateLastActiveMiddleware::class,
        ]);

        $middleware->alias([
            'check.role' => \App\Http\Middleware\CheckActorRoleMiddleware::class
        ]);

        $middleware->redirectGuestsTo(fn(Illuminate\Http\Request $request) => route('auth.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void
    {
        //
    })->create();
