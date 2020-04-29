<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class KeycloakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (App::environment('testing')) {
            return;
        }

        if (config('keycloak.realm_public_key') === null) {
            $publicKey = Cache::get('keycloak.realm_public_key', null);
            if ($publicKey === null) {
                $protocol = config('keycloak.ssl') ? 'https' : 'http';
                $publicKey = Http::withOptions(['verify' => false])
                    ->get($protocol.'://'.config('keycloak.uri').'/auth/realms/'.config('keycloak.realm'))
                    ->json()['public_key'];
                Cache::add('keycloak.realm_public_key', $publicKey);
            }
            config(['keycloak.realm_public_key' => $publicKey]);
        }
    }
}
