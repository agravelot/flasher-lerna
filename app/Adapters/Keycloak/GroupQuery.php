<?php

declare(strict_types=1);

namespace App\Adapters\Keycloak;

class GroupQuery
{
    public ?bool $briefRepresentation;
    public ?int $first;
    public int $max = 100;
    public ?string $search;
}
