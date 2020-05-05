<?php

namespace App\Providers;

use App\Services\Keycloak\Keycloak;
use Illuminate\Support\ServiceProvider;

class KeycloakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('keycloak', static function () {
            return new Keycloak();
        });
    }
}
