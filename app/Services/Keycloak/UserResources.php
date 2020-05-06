<?php

namespace App\Services\Keycloak;

class UserResources
{
    private Keycloak $keycloak;

    public function __construct(Keycloak $keycloak)
    {
        $this->keycloak = $keycloak;
    }

    public function first(?UserQuery $query = null): ?UserRepresentation
    {
        $query ??= new UserQuery();
        $query->max = 1;

        return $this->all($query)[0] ?? null;
    }

    /**
     * @param  UserQuery  $query
     *
     * @return array<UserRepresentation>
     */
    public function all(?UserQuery $query = null): array
    {
        $realm = $this->keycloak->realm;

        $response = $this->keycloak->getClient()
            ->get("/admin/realms/$realm/users?".http_build_query((array) $query));

        $users = [];
        foreach ($response->json() as $user) {
            $users[] = UserRepresentation::fromArray($user);
        }

        return $users;
    }

    public function find(string $ssoId): UserRepresentation
    {
        $realm = $this->keycloak->realm;

        $response = $this->keycloak->getClient()
            ->get("/admin/realms/$realm/users/$ssoId");

        if (! $response->ok()) {
            $response->throw();
        }

        return UserRepresentation::fromArray($response->json());
    }

    public function create(UserRepresentation $user): void
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->post("/admin/realms/$realm/users", $user->toArray());

        if (! $response->successful()) {
            $response->throw();
        }
    }

    public function update(array $data): void
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->put("/admin/realms/$realm/users", $data);

        if (! $response->ok()) {
            $response->throw();
        }
    }

    public function delete(string $ssoId): void
    {
        $realm = $this->keycloak->realm;
        $response = $this->keycloak->getClient()
            ->delete("/admin/realms/$realm/users/$ssoId");

        if ($response->status() !== 204) {
            $response->throw();
        }
    }

    public function count(): int
    {
        $realm = $this->keycloak->realm;

        return  (int) $this->keycloak->getClient()
            ->get("/admin/realms/$realm/users/count")
            ->json();
    }
}
