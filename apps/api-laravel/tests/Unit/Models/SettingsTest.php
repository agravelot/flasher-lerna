<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\SettingType;
use App\Models\Setting;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_non_existent_return_false(): void
    {
        $this->assertFalse(settings()->has('random'));
    }

    public function test_get_non_existent_setting_throw_an_exception(): void
    {
        try {
            settings()->get('random');
            $this->assertTrue(true, 'Missing exception');
        } catch (Exception $exception) {
            $this->assertSame("Unable to find 'random' setting", $exception->getMessage());
        }
    }

    public function test_get_existent_setting(): void
    {
        $setting = factory(Setting::class)->create(['name' => 'john', 'type' => 'string', 'value' => 'doe']);
        $this->assertSame('doe', settings()->get('john'));
    }

    public function test_create_text_area_setting(): void
    {
        $setting = factory(Setting::class)->state(SettingType::TextArea)->create();

        $this->assertSame(SettingType::TextArea, $setting->type->value);
    }

    public function test_x_setting_type_return_a_x(): void
    {
        $types = collect(SettingType::ALIASES_TYPES);

        $types->each(function (string $targetType, string $settingTypeEnum) {
            $setting = factory(Setting::class)->state($settingTypeEnum)->create();

            // If it's an object, we will check the class
            if (is_object($setting->value)) {
                $this->assertSame($targetType, get_class($setting->value));

                return true; // continue
            }
            $this->assertSame($targetType, gettype($setting->value));
        });
    }
}
