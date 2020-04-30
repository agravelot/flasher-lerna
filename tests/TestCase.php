<?php

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;

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
        config(['keycloak.realm_public_key' => 'random']);
    }

    protected function actingAsAdminNotStored(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin);
    }

    protected function actingAsAdmin(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin);
        Passport::actingAs($admin, ['*']);
    }

    protected function actingAsUser(): void
    {
        $user = factory(User::class)->state('user')->make();
        $this->actingAs($user);
        Passport::actingAs($user, ['*']);
    }
}
