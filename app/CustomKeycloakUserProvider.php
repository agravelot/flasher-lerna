<?php


namespace App;


use Vizir\KeycloakWebGuard\Auth\KeycloakWebUserProvider;

class CustomKeycloakUserProvider extends KeycloakWebUserProvider
{
    public function getModel(): string
    {
        return $this->model;
    }
}
