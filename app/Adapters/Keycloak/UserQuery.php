<?php

namespace App\Adapters\Keycloak;

/**
 * Class UserQuery.
 *
 * @see https://www.keycloak.org/docs-api/10.0/rest-api/index.html#_users_resource
 */
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
