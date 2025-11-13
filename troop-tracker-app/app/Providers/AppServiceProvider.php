<?php

namespace App\Providers;

use App\Services\BreadCrumbService;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(BreadCrumbService::class, function ()
        {
            return new BreadCrumbService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->extend(MigrationRepositoryInterface::class, function ($repository, $app)
        {
            return new DatabaseMigrationRepository(
                $app['db'],
                'tt_migrations'
            );
        });
    }
}
