<?php

namespace App\Providers;

use App\SettingsManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/** @noinspection PhpFullyQualifiedNameUsageInspection */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bulma');
        //Paginator::defaultSimpleView('pagination::view');

        $this->app->singleton('App\SettingManager', static function ($app) {
            return new SettingsManager();
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
//            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
