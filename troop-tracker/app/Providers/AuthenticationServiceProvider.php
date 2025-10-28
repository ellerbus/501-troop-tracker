<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\AuthenticationInterface;
use App\Services\XenforoAuthenticationService;
use App\Services\StandaloneAuthenticationService;
use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthenticationInterface::class, function ($app)
        {
            $type = config('tracker.auth.type');

            return match ($type)
            {
                'xenforo' => new XenforoAuthenticationService(),
                default => new StandaloneAuthenticationService(),
            };
        });
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
