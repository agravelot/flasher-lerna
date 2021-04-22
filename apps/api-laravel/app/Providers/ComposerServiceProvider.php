<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\ViewComposers\SocialMediaComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SocialMediaComposer::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::creator('layouts.partials._navbar_socials', SocialMediaComposer::class);
    }
}
