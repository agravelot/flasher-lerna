<?php

declare(strict_types=1);

namespace App;

use Vizir\KeycloakWebGuard\Auth\KeycloakWebUserProvider;

class CustomKeycloakUserProvider extends KeycloakWebUserProvider
{
    public function getModel(): string
    {
        return $this->model;
    }
}
