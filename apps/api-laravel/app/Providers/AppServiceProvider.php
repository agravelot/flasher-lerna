<?php

declare(strict_types=1);

namespace App\Providers;

use App\ResponsiveImageGenerator as CustomResponsiveImageGenerator;
use App\SettingsManager;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImageGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton('App\SettingManager', static function () {
            return new SettingsManager();
        });

        if (config('app.new_conversions', false)) {
            $this->app->bind(ResponsiveImageGenerator::class, CustomResponsiveImageGenerator::class);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
