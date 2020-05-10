<?php

namespace Tests;

use App\Facades\Keycloak;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use App\Adapters\Keycloak\UserRepresentation;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        App::setLocale('en');
        $this->withoutMiddleware(VerifyCsrfToken::class);
        // Workaround for resource wrapping issue during tests
        // https://github.com/laravel/framework/issues/26021
        JsonResource::$wrap = 'data';
        Storage::fake();
        Storage::fake('s3');
        foreach (Keycloak::users()->all() as $user) {
            /* @var UserRepresentation $user */
            Keycloak::users()->delete($user->id);
        }
    }

    protected function actingAsAdminNotStored(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin, 'api');
        $this->actingAs($admin, 'web');
    }

    protected function actingAsAdmin(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin, 'api');
        $this->actingAs($admin, 'web');
    }

    protected function actingAsUser(): void
    {
        $user = factory(User::class)->state('user')->make();
        $this->actingAs($user, 'api');
        $this->actingAs($user, 'web');
    }
}
