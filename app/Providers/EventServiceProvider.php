<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Setting;
use App\Models\Invitation;
use App\Observers\AlbumObserver;
use App\Observers\SettingObserver;
use App\Observers\InvitationObserver;
use Illuminate\Auth\Events\Registered;
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
        Setting::observe(SettingObserver::class);
        Invitation::observe(InvitationObserver::class);
    }
}
