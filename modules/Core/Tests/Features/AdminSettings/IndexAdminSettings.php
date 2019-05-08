<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Features\AdminSettings;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexAdminSettings extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeSettings()
    {
        $this->actingAsAdmin();
        settings()->set(['app_name' => 'Flasher'])->save();

        $response = $this->getJson('/api/admin/settings');

        $response->assertStatus(200)
            ->assertJson(['app_name' => 'Flasher']);
    }

    public function testUserCannotSeeSettings()
    {
        $this->actingAsUser();
        settings()->set(['app_name' => 'Flasher'])->save();

        $response = $this->getJson('/api/admin/settings');

        $response->assertStatus(403);
    }

    public function testGuestCannotSeeSettings()
    {
        settings()->set(['app_name' => 'Flasher'])->save();

        $response = $this->getJson('/api/admin/settings');

        $response->assertStatus(401);
    }
}
