<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Feature\AdminSettings;

use Tests\TestCase;
use Modules\Core\Entities\Setting;
use Modules\Core\Enums\SettingType;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdateSetting()
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'name' => 'test', 'value' => 'Flasher', 'type' => 'string', 'description' => null,
        ]);

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
        $countBefore = Setting::all()->count();
        $this->actingAsUser();
        $setting = factory(Setting::class)->create(['value' => 'testValue']);
        $setting->value = 'newValue';

        $response = $this->updateSetting($setting);

        $response->assertStatus(403);
        $this->assertCount(++$countBefore, Setting::all());
        $this->assertSame('testValue', $setting->fresh()->value);
    }

    public function testGuestCannotUpdateSettings()
    {
        $countBefore = Setting::all()->count();
        $setting = factory(Setting::class)->create(['name' => 'test', 'type' => 'string', 'value' => 'testValue']);

        $setting->value = 'newValue';
        $response = $this->updateSetting($setting);

        $response->assertStatus(401);
        $this->assertCount(++$countBefore, Setting::all());
        $this->assertSame('testValue', $setting->fresh()->value);
    }

//    public function test_default_boolean_setting_is_false()
//    {
//        $setting = factory(Setting::class)->create(['name' => 'bool_setting', 'type' => 'bool', 'value' => null]);
//
//        $this->assertFalse($setting->value);
//    }

    public function test_a_numeric_setting_can_store_string_with_value_to_zero()
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'name' => 'bool_setting', 'type' => SettingType::Numeric, 'value' => 42,
        ]);
        $this->assertSame(42, $setting->value);

        $setting->value = 'randomString';
        $response = $this->updateSetting($setting);

        $response->assertStatus(200);
        $this->assertSame(0, $setting->fresh()->value);
    }

    public function test_setting_type_to_media_can_store_media()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
