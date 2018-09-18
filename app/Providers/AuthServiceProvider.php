<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\User;
use App\Policies\AdminAlbumPolicy;
use App\Policies\AdminContactPolicy;
use App\Policies\AdminCosplayerPolicy;
use App\Policies\AdminUserPolicy;
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
        Album::class => AdminAlbumPolicy::class,
        Contact::class => AdminContactPolicy::class,
        Cosplayer::class => AdminCosplayerPolicy::class,
        User::class => AdminUserPolicy::class,
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
