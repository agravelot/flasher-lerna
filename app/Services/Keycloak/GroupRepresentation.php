<?php


namespace App\Services\Keycloak;


class GroupRepresentation
{
    public ?string $id;
    public array $access;
    public array $attributes;
    public array $clientRoles;
    public string $name;
    public string $path;
    public array $realmRoles;
    public array $subGroups;

    public static function fromArray(array $data): self {
        $group = new self();
        $group->id = $data['id'] ?? null;
        $group->attributes = $data['attributes'] ?? [];
        $group->access = $data['access'] ?? [];
        $group->clientRoles = $data['clientRoles'] ?? [];
        $group->realmRoles = $data['realmRoles'] ?? [];
        $group->subGroups = $data['subGroups'] ?? [];

        return $group;
    }
}
