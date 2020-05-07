<?php

namespace App\Providers;

use App\Services\Keycloak\Keycloak;
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

        $this->app->when(KeycloakService::class)
            ->needs(ClientInterface::class)
            ->give(fn($app) => new Client(['verify' => config('keycloak.verify_ssl', true)]));
    }
}
