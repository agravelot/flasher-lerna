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
use App\Models\GoldenBookPost;
use App\Observers\UserObserver;
use App\Observers\AlbumObserver;
use App\Observers\ContactObserver;
use App\Observers\CategoryObserver;
use App\Observers\CosplayerObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Observers\GoldenBookPostObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        Album::observe(AlbumObserver::class);
        Category::observe(CategoryObserver::class);
        Contact::observe(ContactObserver::class);
        Cosplayer::observe(CosplayerObserver::class);
        GoldenBookPost::observe(GoldenBookPostObserver::class);
        User::observe(UserObserver::class);
    }
}
