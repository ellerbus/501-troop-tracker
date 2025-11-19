<?php

namespace App\Providers;

use App\Enums\TrooperPermissions;
use App\Models\Trooper;
use App\Observers\TrooperObserver;
use App\Services\BreadCrumbService;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use ValueError;

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
        //
        //  MIGRATION
        //
        $this->app->extend(MigrationRepositoryInterface::class, function ($repository, $app)
        {
            return new DatabaseMigrationRepository(
                $app['db'],
                'tt_migrations'
            );
        });

        //
        //    OBSERVER BOOTS
        //
        Trooper::observe(TrooperObserver::class);

        //
        //  BLADE BOOTS
        //
        Blade::if('permission', function (TrooperPermissions|string $role): bool
        {
            if (Auth::check())
            {
                $user = Auth::user();

                if ($user)
                {
                    if (is_string($role))
                    {
                        try
                        {
                            $role = TrooperPermissions::from($role);
                        }
                        catch (ValueError $e)
                        {
                            throw new InvalidArgumentException("Invalid permission role: '{$role}'");
                        }
                    }

                    return $user->permissions === $role;
                }
            }

            return false;
        });
    }
}
