<?php

declare(strict_types=1);

namespace App\Adapters\Keycloak;

class GroupResources
{
    private Keycloak $keycloak;

    public function __construct(Keycloak $keycloak)
    {
        $this->keycloak = $keycloak;
    }

    public function firstMember(string $id): ?UserRepresentation
    {
        return $this->members($id, null, 1)[0] ?? null;
    }

    /**
     * @return array<UserRepresentation>
     */
    public function members(string $id, ?int $first = null, ?int $max = null): array
    {
        $realm = $this->keycloak->realm;

        $response = $this->keycloak->getClient()
            ->get("/admin/realms/$realm/groups/$id/members?".http_build_query(compact('first', 'max')));

        if (! $response->ok()) {
            $response->throw();
        }

        $users = [];
        foreach ($response->json() as $user) {
            $users[] = UserRepresentation::fromArray($user);
        }

        return $users;
    }

    public function first(?GroupQuery $query = null): ?GroupRepresentation
    {
        $query ??= new GroupQuery();
        $query->max = 1;

        return $this->all($query)[0] ?? null;
    }

    /**
     * @param  GroupQuery  $query
     *
     * @return array<GroupRepresentation>
     */
    public function all(?GroupQuery $query = null): array
    {
        $realm = $this->keycloak->realm;

        $response = $this->keycloak->getClient()
            ->get("/admin/realms/$realm/groups?".http_build_query((array) $query));

        if (! $response->ok()) {
            $response->throw();
        }

        $groups = [];
        foreach ($response->json() as $user) {
            $groups[] = GroupRepresentation::fromArray($user);
        }

        return $groups;
    }
}
