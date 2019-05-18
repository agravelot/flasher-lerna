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

class UpdateAdminSettings extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdateSetting()
    {
        $this->actingAsAdmin();
        $setting = Setting::create(['name' => 'test', 'value' => 'Flasher', 'type' => 'string']);

        $setting->value = 'newValue';
        $response = $this->updateSetting($setting);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test', 'value' => 'newValue', 'type' => 'string', 'description' => null,
                ],
            ]);

        $this->assertSame('newValue', Setting::find('test')->value);
    }

    private function updateSetting(Setting $setting): TestResponse
    {
        return $this->json('patch', "/api/admin/settings/{$setting->name}", ['value' => $setting->value]);
    }

    public function testUserCannotUpdateSettings()
    {
        $this->actingAsUser();
        $setting = Setting::create(['name' => 'test', 'value' => 'Flasher', 'type' => 'string']);
        $setting->value = 'newValue';

        $response = $this->updateSetting($setting);

        $response->assertStatus(403);
        $this->assertCount(1, Setting::all());
        $this->assertSame('testValue', Setting::find('test')->value);
    }

    public function testGuestCannotUpdateSettings()
    {
        $setting = Setting::create(['name' => 'test', 'value' => 'Flasher', 'type' => 'string']);
        $setting->value = 'newValue';

        $response = $this->updateSetting($setting);

        $response->assertStatus(401);
        $this->assertCount(1, Setting::all());
        $this->assertSame('testValue', Setting::find('test')->value);
    }
}
