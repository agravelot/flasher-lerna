<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Unit\AdminPages;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\Setting;
use Modules\Core\Enums\SettingType;
use Spatie\MediaLibrary\Models\Media;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_non_existent_return_false()
    {
        $this->assertFalse(settings()->has('random'));
    }

    public function test_get_non_existent_setting_throw_an_exception()
    {
        try {
            settings()->get('random');
            $this->assertTrue(true, 'Missing exception');
        } catch (Exception $exception) {
            $this->assertSame("Unable to find 'random' setting", $exception->getMessage());
        }
    }

    public function test_get_existent_setting()
    {
        $setting = factory(Setting::class)->create(['name' => 'john', 'value' => 'doe']);
        $this->assertSame('doe', settings()->get('john'));
    }

    public function test_create_text_area_setting()
    {
        $setting = factory(Setting::class)->create(['type' => SettingType::TextArea]);

        $this->assertSame(SettingType::TextArea, $setting->type->value);
    }

    public function test_x_setting_type_return_a_x()
    {
        $types = collect([
            SettingType::String => 'string',
            SettingType::Numeric => 'integer',
            SettingType::Boolean => 'boolean',
            SettingType::TextArea => 'string',
            // SettingType::Json => '??',
            // SettingType::Media => Media::class,
        ]);

        $types->each(function (string $targetType, string $settingTypeEnum) {
            $setting = factory(Setting::class)->state($settingTypeEnum)->create();

            $this->assertSame($targetType, gettype($setting->value));
        });
    }
}
