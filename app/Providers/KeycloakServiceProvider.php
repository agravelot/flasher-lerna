<?php

namespace App\Providers;

use App\Services\Keycloak\Keycloak;
use App\Services\KeycloakWebService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Vizir\KeycloakWebGuard\Services\KeycloakService;

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

        $this->app->bind('keycloak-web', function($app) {
            return $app->make(KeycloakWebService::class);
        });

        $this->app->when(KeycloakWebService::class)
            ->needs(ClientInterface::class)
            ->give(static fn ($app) => new Client(['verify' => config('keycloak.verify_ssl', true)]));
    }
}
