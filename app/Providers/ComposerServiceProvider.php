<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\CategoriesComposer;
use App\Http\ViewComposers\SocialMediaComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(SocialMediaComposer::class);
        $this->app->singleton(CategoriesComposer::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        View::composer('layouts.partials._navbar_socials', SocialMediaComposer::class);
        View::composer('layouts.partials._navbar_categories', CategoriesComposer::class);
    }
}
