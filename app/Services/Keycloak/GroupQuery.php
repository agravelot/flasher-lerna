<?php


namespace App\Services\Keycloak;


class GroupQuery
{
    public ?bool $briefRepresentation;
    public ?int $first;
    public int $max = 100;
    public ?string $search;
}
