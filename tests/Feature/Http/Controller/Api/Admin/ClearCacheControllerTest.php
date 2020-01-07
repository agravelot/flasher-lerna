<?php

namespace Tests\Feature\Http\Controller\Api\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearCacheControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_clear_cache(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/admin/clear-cache');

        $response->assertOk();
    }

    public function test_user_can_not_clear_cache(): void
    {
        $this->actingAsUser();

        $response = $this->getJson('/api/admin/clear-cache');

        $response->assertStatus(403);
    }

    public function test_guest_can_not_clear_cache(): void
    {
        $response = $this->getJson('/api/admin/clear-cache');

        $response->assertUnauthorized();
    }
}
