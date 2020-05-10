<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Api;

use App\Facades\Keycloak;
use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_return_valid_dashboard(): void
    {
        Keycloak::shouldReceive('users->count')
            ->andReturn(10);
        $this->actingAsAdmin();

        $response = $this->json('get', '/api/admin/dashboard');

        $response->assertJson([
            'cosplayersCount' => Cosplayer::count(),
            'usersCount' => 10,
            'albumsCount' => Album::count(),
            'contactsCount' => Contact::count(),
        ]);
    }
}
