<?php

declare(strict_types=1);

namespace Tests;

use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
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
        Http::fake([
            config('keycloak-web.base_url').'/realms/master/protocol/openid-connect/token' => Http::response(['access_token' => '']),
        ]);
//        foreach (Keycloak::users()->all() as $user) {
//            /* @var UserRepresentation $user */
//            Keycloak::users()->delete($user->id);
//        }
    }

    protected function actingAsAdminNotStored(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin, 'api');
    }

    protected function actingAsAdmin(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin, 'api');
    }

    protected function actingAsUser(): void
    {
        $user = factory(User::class)->state('user')->make();
        $this->actingAs($user, 'api');
    }
}
