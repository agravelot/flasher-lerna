<?php


namespace App\Services\Keycloak;


class UserQuery
{
    public ?bool $briefRepresentation;
    public ?string $email;
    public ?int $first;
    public ?string $firstName;
    public ?string $lastName;
    public int $max = 100;
    public ?string $search;
    public ?string $username;
}
