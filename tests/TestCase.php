<?php

namespace Tests;

use App\Exceptions\Handler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Fluent;
use InvalidArgumentException;
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
    }

    protected function actingAsAdminNotStored(): void
    {
        $admin = factory(User::class)->state('admin')->make();
        $this->actingAs($admin);
    }

    protected function actingAsAdmin(): void
    {
        $admin = factory(User::class)->state('admin')->create();
        $this->actingAs($admin);
        Passport::actingAs($admin, ['*']);
    }

    protected function actingAsUser(): void
    {
        $user = factory(User::class)->state('user')->create();
        $this->actingAs($user);
        Passport::actingAs($user, ['*']);
    }
}
