<?php

declare(strict_types=1);

namespace App\Adapters\Keycloak;

use App\Models\User;
use Faker\Factory;

/**
 * Class UserRepresentation according keycloak api.
 *
 * @see https://www.keycloak.org/docs-api/10.0/rest-api/index.html#_userrepresentation
 */
class UserRepresentation
{
    public array $access = [];
    public array $attributes = [];
    public array $clientConsents = [];
    public int $createdTimestamp;
    public array $credentials = [];
    public ?string $email;
    public bool $emailVerified;
    public bool $enabled = true;
    public bool $totp = false;
    public ?string $firstName;
    public ?string $id;
    public ?string $lastName;
    public array $realmRoles = [];
    public array $groups = [];
    public string $username;
    public array $requiredActions = [];

    public function addCredential(Credential $credential): void
    {
        $this->credentials[] = $credential;
    }

    public function addGroup(string $group): void
    {
        $this->groups[] = $group;
    }

    public static function fromArray(array $data): self
    {
        $user = new self();
        $user->id = $data['sub'] ?? $data['id'] ?? null;
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;
        $user->emailVerified = $data['emailVerified'];
        $user->groups = $data['groups'] ?? [];
        $user->enabled = $data['enabled'];
        $user->firstName = $data['firstName'] ?? null;
        $user->lastName = $data['lastName'] ?? null;
        $user->attributes = $data['attributes'] ?? [];

        return $user;
    }

    public function toUser(): User
    {
        $user = new User();
        $user->id = $this->id;
        $user->email = $this->email;
        $user->email_verified = $this->emailVerified;
        $user->notify_on_album_published = $this->attributes['notify_on_album_published'] ?? false;
        $user->groups = $this->groups;
        $user->realm_roles = $this->realmRoles;
        //$user->realm_access = $this->realmAccess;
        $user->enabled = $this->enabled;
        $user->lastName = $this->lastName;
        $user->firstName = $this->firstName;

        return $user;
    }

    public static function factory(array $data = []): self
    {
        $faker = Factory::create();
        //$data['id'] ??= $faker->uuid;
        $data['username'] ??= $faker->userName;
        $data['email'] ??= $faker->email;
        $data['emailVerified'] ??= true;
        $data['groups'] ??= [];
        $data['enabled'] ??= true;
        $data['firstName'] = null;
        $data['lastName'] = null;
        $data['attributes'] ??= ['notify_on_album_published' => true];

        return self::fromArray($data);
    }

    public function toArray(): array
    {
        $credentials = [];
        foreach ($this->credentials as $credential) {
            $credentials[] = (array) $credential;
        }

        return [
            'email' => $this->email,
            'username' => $this->username,
            'emailVerified' => $this->emailVerified,
            'enabled' => $this->enabled,
            'totp' => $this->totp,
            'credentials' => $credentials,
            'groups' => $this->groups,
            'attributes' => $this->attributes,
            'requiredActions' => $this->requiredActions,
        ];
    }
}
