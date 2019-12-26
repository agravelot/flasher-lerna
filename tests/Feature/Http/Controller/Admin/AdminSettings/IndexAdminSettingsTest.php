<?php

namespace Tests\Feature\Http\Controller\Admin\AdminSettings;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class IndexAdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeSettings()
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create(['name' => 'test', 'value' => 'Flasher', 'type' => 'string']);

        $response = $this->getSettings();

        $response->assertOk()
            ->assertJsonFragment(['name' => 'test', 'value' => 'Flasher', 'type' => 'string', 'description' => null]);
    }

    private function getSettings(): TestResponse
    {
        return $this->getJson('/api/admin/settings');
    }

    public function testUserCannotSeeSettings()
    {
        $this->actingAsUser();

        $response = $this->getSettings();

        $response->assertStatus(403);
    }

    public function testGuestCannotSeeSettings()
    {
        $response = $this->getSettings();

        $response->assertStatus(401);
    }
}
