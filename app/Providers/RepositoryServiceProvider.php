<?php

namespace App\Providers;

use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\ContactRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\ContactRepository;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\Contracts\GoldenBookRepository;
use App\Repositories\Contracts\PictureRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\CosplayerRepositoryEloquent;
use App\Repositories\GoldenBookRepositoryEloquent;
use App\Repositories\PictureRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;
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
        
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(AlbumRepository::class, AlbumRepositoryEloquent::class);
        $this->app->bind(CosplayerRepository::class, CosplayerRepositoryEloquent::class);
        $this->app->bind(ContactRepository::class, ContactRepositoryEloquent::class);
        $this->app->bind(CategoryRepository::class, CategoryRepositoryEloquent::class);
        $this->app->bind(GoldenBookRepository::class, GoldenBookRepositoryEloquent::class);
        //:end-bindings:
    }
}
