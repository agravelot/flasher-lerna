<?php

namespace App\Providers;

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
        if (config('keycloak.realm_public_key') === null) {
            $publicKey = Cache::get('keycloak.realm_public_key', null);
            if ($publicKey === null) {
                $publicKey = Http::withOptions(['verify' => false])
                    ->get('https://'.env('KEYCLOAK_URI').'/auth/realms/'.config('keycloak.realm'))
                    ->json()['public_key'];
                Cache::add('keycloak.realm_public_key', $publicKey);
            }
            config(['keycloak.realm_public_key' => $publicKey]);
        }
    }
}
