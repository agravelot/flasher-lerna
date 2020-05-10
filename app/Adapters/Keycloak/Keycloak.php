<?php

declare(strict_types=1);

namespace App\Adapters\Keycloak;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Keycloak
{
    public string $baseUrl;
    public string $username;
    public string $password;
    public string $grantType;
    public string $clientId;
    public string $realm;
    private string $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('keycloak.url').'/auth';
        $this->username = config('keycloak.master.username', 'admin');
        $this->password = config('keycloak.master.password', 'admin');
        $this->grantType = 'password';
        $this->clientId = 'admin-cli';
        $this->realm = config('keycloak.realm');
    }

    public function users(): UserResources
    {
        return new UserResources($this);
    }

    public function groups(): GroupResources
    {
        return new GroupResources($this);
    }

    public function getAccessToken(): string
    {
        if (! empty($this->accessToken)) {
            return $this->accessToken;
        }

        $response = Http::withOptions(['verify' => config('keycloak.verify_ssl')])
            ->baseUrl($this->baseUrl)
            ->asForm()->post(
                '/realms/master/protocol/openid-connect/token',
                [
                    'username' => $this->username,
                    'password' => $this->password,
                    'grant_type' => $this->grantType,
                    'client_id' => $this->clientId,
                ]
            );

        if (! $response->ok()) {
            $response->throw();
        }

        return $this->accessToken = $response->json()['access_token'];
    }

    public function getClient(): PendingRequest
    {
        return Http::withOptions(['verify' => config('keycloak.verify_ssl')])
            ->baseUrl($this->baseUrl)
            ->withToken($this->getAccessToken());
    }
}
