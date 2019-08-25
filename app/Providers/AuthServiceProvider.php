<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Providers;

use App\Models\User;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Cosplayer;
use App\Models\SocialMedia;
use App\Models\Testimonial;
use App\Policies\UserPolicy;
use App\Policies\AlbumPolicy;
use Laravel\Passport\Passport;
use App\Policies\ContactPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CosplayerPolicy;
use App\Policies\SocialMediaPolicy;
use App\Policies\TestimonialsPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Album::class => AlbumPolicy::class,
        Contact::class => ContactPolicy::class,
        Cosplayer::class => CosplayerPolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Testimonial::class => TestimonialsPolicy::class,
        SocialMedia::class => SocialMediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('dashboard', 'App\Policies\AdminPolicy@dashboard');

        Passport::routes();
    }
}
