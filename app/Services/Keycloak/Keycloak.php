<?php

namespace App\Services\Keycloak;

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
        $this->username = 'admin';
        $this->password = 'admin';
        $this->grantType = 'password';
        $this->clientId = 'admin-cli';
        $this->realm = config('keycloak.realm');
    }

    public function users(): UserResources
    {
        return new UserResources($this);
    }

    public function getAccessToken(): string
    {
        if (! empty($this->accessToken)) {
            return $this->accessToken;
        }

        $response = $this->getClient()
            ->asForm()->post(
                '/realms/master/protocol/openid-connect/token',
                [
                    'username' => $this->username,
                    'password' => $this->password,
                    'grant_type' => $this->grantType,
                    'client_id' => $this->clientId,
                ]
            );

        return $this->accessToken = $response->json()['access_token'];
    }

    public function getClient(): PendingRequest
    {
        return Http::withOptions([
            'verify' => config('keycloak.verify_ssl'),
        ])->baseUrl($this->baseUrl);
    }
}
