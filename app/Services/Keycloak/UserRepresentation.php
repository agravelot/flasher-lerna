<?php


namespace App\Services\Keycloak;


class UserRepresentation
{
    public array $access;
    public array $attributes;
    public array $clientConsents;
    public int $createdTimestamp;
    public array $credentials;
    public string $email;
    public bool $emailVerified;
    public bool $enabled;
    public string $firstName;
    public string $id;
    public string $lastName;
    public array $realmRoles;
    public string $username;
}
