<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Tests\Features\AdminPages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\Setting;
use Tests\TestCase;

class SettingsTests extends TestCase
{
    use RefreshDatabase;

    public function test_get_non_existent_setting_throw_an_exception()
    {
        try {
            settings()->get('random');
            $this->assertTrue(true, 'Missing exception');
        } catch (\Exception $exception) {
            $this->assertSame("Unable to find 'random' setting", $exception->getMessage());
        }
    }

    public function test_get_existent_setting()
    {
        $setting = factory(Setting::class)->create(['name' => 'john', 'value' => 'doe']);
        $this->assertSame('doe', settings()->get('john'));
    }
}