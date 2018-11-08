<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\GoldenBookPost;
use App\Models\User;
use App\Policies\AlbumPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ContactPolicy;
use App\Policies\CosplayerPolicy;
use App\Policies\GoldenBookPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        GoldenBookPost::class => GoldenBookPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('dashboard', 'App\Policies\AdminPolicy@dashboard');
    }
}
