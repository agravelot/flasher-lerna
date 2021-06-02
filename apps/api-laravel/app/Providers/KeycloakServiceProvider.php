<?php

declare(strict_types=1);

namespace App\Providers;

use App\Adapters\Keycloak\Keycloak;
use Illuminate\Support\ServiceProvider;

class KeycloakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('keycloak', static function () {
            return new Keycloak();
        });
    }
}
