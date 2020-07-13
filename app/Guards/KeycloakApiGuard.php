<?php

declare(strict_types=1);

namespace App\Guards;

use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use KeycloakGuard\Exceptions\ResourceAccessNotAllowedException;
use KeycloakGuard\Exceptions\UserNotFoundException;
use KeycloakGuard\KeycloakGuard;

/**
 * Class KeycloakApiGuard.
 *
 * @see KeycloakGuard
 */
class KeycloakApiGuard implements Guard
{
    private $config;
    private $user;
    private $provider;
    private $decodedToken;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->config = config('keycloak');
        $this->user = null;
        $this->provider = $provider;
        $this->decodedToken = null;
        $this->request = $request;

        $this->authenticate();
    }

    /**
     * Decode token, validate and authenticate user.
     */
    private function authenticate(): void
    {
        if (! $this->request->bearerToken()) {
            return;
        }

        $tokens = explode('.', $this->request->bearerToken());

        if ($tokens === false || count($tokens) !== 3) {
            return;
        }

        [$headb64, $bodyb64, $cryptob64] = explode('.', $this->request->bearerToken());
        $this->decodedToken = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));

        if ($this->decodedToken) {
            $this->validate([
                $this->config['user_provider_credential'] => $this->decodedToken->{$this->config['token_principal_attribute']},
            ]);
        }
    }

    /**
     * Determine if the current user is authenticated.
     */
    public function check(): bool
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the guard has a user instance.
     */
    public function hasUser(): bool
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     */
    public function guest(): bool
    {
        return ! $this->check();
    }

    /**
     * Get the currently authenticated user.
     */
    public function user(): ?Authenticatable
    {
        if (is_null($this->user)) {
            return null;
        }

        if ($this->config['append_decoded_token']) {
            $this->user->token = $this->decodedToken;
        }

        return $this->user;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return void|string
     */
    public function id()
    {
        if ($user = $this->user()) {
            return $this->user()->id;
        }
    }

    /**
     * Validate a user's credentials.
     */
    public function validate(array $credentials = []): bool
    {
        if (! $this->decodedToken) {
            return false;
        }

        $this->validateResources();

        if ($this->config['load_user_from_database']) {
            $user = $this->provider->retrieveByCredentials($credentials);

            if (! $user) {
                throw new UserNotFoundException('User not found. Credentials: '.json_encode($credentials));
            }
        } else {
            $class = $this->provider->getModel();
            $user = new $class();
        }

        $this->setUser($user);

        return true;
    }

    /**
     * Set the current user.
     */
    public function setUser(Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Validate if authenticated user has a valid resource.
     */
    private function validateResources(): void
    {
        $token_resource_access = array_keys((array) ($this->decodedToken->resource_access ?? []));
        $allowed_resources = explode(',', $this->config['allowed_resources']);

        if (count(array_intersect($token_resource_access, $allowed_resources)) === 0) {
            throw new ResourceAccessNotAllowedException('The decoded JWT token has not a valid `resource_access` allowed by API. Allowed resources by API: '.$this->config['allowed_resources']);
        }
    }

    /**
     * Returns full decoded JWT token from athenticated user.
     *
     * @return mixed|null
     */
    public function token()
    {
        return json_encode($this->decodedToken);
    }

    /**
     * Check if authenticated user has a especific role into resource.
     * @param string $resource
     * @param string $role
     */
    public function hasRole($resource, $role): bool
    {
        $token_resource_access = (array) $this->decodedToken->resource_access;
        if (array_key_exists($resource, $token_resource_access)) {
            $token_resource_values = (array) $token_resource_access[$resource];

            if (array_key_exists('roles', $token_resource_values) &&
                in_array($role, $token_resource_values['roles'], true)) {
                return true;
            }
        }

        return false;
    }
}
