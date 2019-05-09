<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Features\AdminSettings;

use Tests\TestCase;
use Modules\Core\Entities\Setting;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexAdminSettings extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeSettings()
    {
        $this->actingAsAdmin();
        Setting::create(['name' => 'app_name', 'value' => 'Flasher']);

        $response = $this->getSettings();

        $response->assertStatus(200)
            ->assertJson(['data' => ['app_name' => 'Flasher']]);
    }

    private function getSettings(): TestResponse
    {
        return $this->getJson('/api/admin/settings');
    }

    public function testUserCannotSeeSettings()
    {
        $this->actingAsUser();
        Setting::create(['name' => 'app_name', 'value' => 'Flasher']);

        $response = $this->getSettings();

        $response->assertStatus(403);
    }

    public function testGuestCannotSeeSettings()
    {
        Setting::create(['name' => 'app_name', 'value' => 'Flasher']);

        $response = $this->getSettings();

        $response->assertStatus(401);
    }
}
