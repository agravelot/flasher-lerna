<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controller\Admin\AdminSettings;

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class UpdateAdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanUpdateSetting(): void
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'name' => 'test', 'value' => 'Flasher', 'type' => 'string', 'description' => null,
        ]);

        $setting->value = 'newValue';
        $response = $this->updateSetting($setting);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'name' => 'test', 'value' => 'newValue', 'type' => 'string', 'description' => null,
                ],
            ]);

        $this->assertSame('newValue', $setting->fresh()->value);
    }

    public function test_admin_can_update_image_as_value(): void
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'name' => 'test', 'value' => UploadedFile::fake()->image('test.jpg'), 'type' => SettingType::Media,
            'description' => null,
        ]);

        $response = $this->updateSetting($setting, UploadedFile::fake()->image('new.jpg'));

        $response->assertOk()->assertJsonPath('name', 'new.jpg');

        $this->assertSame('new.jpg', $setting->fresh()->value->file_name);
    }

    private function updateSetting(Setting $setting, ?UploadedFile $media = null): TestResponse
    {
        if ($setting->type->value === SettingType::Media) {
            return $this->post("/api/admin/settings/{$setting->id}", ['_method' => 'PATCH', 'file' => $media], ['accept' => 'application/json']);
        }

        return $this->json('patch', "/api/admin/settings/{$setting->id}", ['value' => $setting->value]);
    }

    public function testUserCannotUpdateSettings(): void
    {
        $countBefore = Setting::all()->count();
        $this->actingAsUser();
        $setting = factory(Setting::class)->create(['type' => SettingType::String, 'value' => 'testValue']);
        $setting->value = 'newValue';

        $response = $this->updateSetting($setting);

        $response->assertStatus(403);
        $this->assertCount(++$countBefore, Setting::all());
        $this->assertSame('testValue', $setting->fresh()->value);
    }

    public function testGuestCannotUpdateSettings(): void
    {
        $countBefore = Setting::all()->count();
        $setting = factory(Setting::class)->create(['type' => 'string', 'value' => 'testValue']);

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

    public function test_a_numeric_setting_can_store_string_with_value_to_zero(): void
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'type' => SettingType::Numeric, 'value' => 42,
        ]);
        $this->assertSame(42, $setting->value);

        $setting->value = 'randomString';
        $response = $this->updateSetting($setting);

        $response->assertOk();
        $this->assertSame(0, $setting->fresh()->value);
    }

    public function test_setting_type_to_media_can_store_media(): void
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'type' => SettingType::Media, 'value' => null,
        ]);
        $this->assertSame(null, $setting->value);

        $setting->value = UploadedFile::fake()->image('test.png');

        $this->assertInstanceOf(Media::class, $setting->fresh()->value);
    }

    public function test_setting_type_to_media_can_update_media(): void
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'type' => SettingType::Media, 'value' => null,
        ]);

        $setting->value = UploadedFile::fake()->image('test.png');
        $this->updateSetting($setting);

        $this->assertInstanceOf(Media::class, $setting->fresh()->value);
        $this->assertSame('test.png', ($setting->fresh()->value)->file_name);
    }

    public function test_email_setting_type_cannot_store_bad_email(): void
    {
        $this->actingAsAdmin();
        $setting = factory(Setting::class)->create([
            'type' => SettingType::Email, 'value' => null,
        ]);

        $setting->value = 'badEmail';
        $response = $this->updateSetting($setting);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('value');
    }
}
