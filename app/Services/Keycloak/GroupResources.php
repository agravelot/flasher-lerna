<?php

namespace App\Services\Keycloak;

class GroupResources
{
    private Keycloak $keycloak;

    public function __construct(Keycloak $keycloak)
    {
        $this->keycloak = $keycloak;
    }

    public function firstMember(string $group): ?UserRepresentation
    {
        return $this->members($group, null, 1)[0] ?? null;
    }

    /**
     * @return array<UserRepresentation>
     */
    public function members(string $group, ?int $first = null, ?int $max = null): array
    {
        $realm = $this->keycloak->realm;

        $response = $this->keycloak->getClient()
            ->get("/admin/realms/$realm/groups/$group/members?".http_build_query(compact('first', 'max')));

        $users = [];
        foreach ($response->json() as $user) {
            $users[] = $this->makeUser($user);
        }

        return $users;
    }

    private function makeUser(array $data): UserRepresentation
    {
        $user = new UserRepresentation();
        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;
        $user->emailVerified = (bool) $data['emailVerified'];

        return $user;
    }
}
