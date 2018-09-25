<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\Contracts\AlbumRepository::class, \App\Repositories\AlbumRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\CosplayerRepository::class, \App\Repositories\CosplayerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\ContactRepository::class, \App\Repositories\ContactRepositoryEloquent::class);
        //:end-bindings:
    }
}
