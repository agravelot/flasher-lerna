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
use App\Http\ViewComposers\SocialMediaComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register():void
    {
        $this->app->singleton(SocialMediaComposer::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot():void
    {
        View::composer('layouts.partials._navbar_socials', SocialMediaComposer::class);
    }
}
