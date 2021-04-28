<?php

declare(strict_types=1);

namespace App\Services;

use Vizir\KeycloakWebGuard\Services\KeycloakService;

class KeycloakWebService extends KeycloakService
{
    public function getUserProfile($credentials): array
    {
        $user = parent::getUserProfile($credentials);
        $decodedToken = $this->parseAccessToken($credentials['access_token']);
        $user['id'] = $decodedToken['sub'];
        $user['username'] = $decodedToken['preferred_username'];
        $user['email_verified'] = $decodedToken['email_verified'];
        $user['realm_access'] = $decodedToken['realm_access'];
        $user['resource_access'] = $decodedToken['resource_access'];

        return $user;
    }
}
