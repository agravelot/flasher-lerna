<?php

namespace App\Providers;

use App\CustomAuthGrant;
use App\Models\Album;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\Invitation;
use App\Models\SocialMedia;
use App\Models\Testimonial;
use App\Models\User;
use App\Policies\AlbumPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ContactPolicy;
use App\Policies\CosplayerPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\SocialMediaPolicy;
use App\Policies\TestimonialsPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use League\OAuth2\Server\Grant\AuthCodeGrant;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<string, string>
     */
    protected $policies = [
        Album::class => AlbumPolicy::class,
        Contact::class => ContactPolicy::class,
        Cosplayer::class => CosplayerPolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Testimonial::class => TestimonialsPolicy::class,
        SocialMedia::class => SocialMediaPolicy::class,
        Invitation::class => InvitationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('dashboard', 'App\Policies\AdminPolicy@dashboard');

        Passport::routes();

        $this->app->extend(AuthCodeGrant::class, static function($service) {
            return new CustomAuthGrant($service);
        });
    }
}
