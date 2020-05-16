<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_settings_refresh_cached_values(): void
    {
        /** @var Collection $cacheBefore */
        $cacheBefore = Cache::get(Setting::SETTINGS_CACHE_KEY);

        settings()->set('app_name', 'random_new_name');
        /** @var Collection $cacheAfter */
        $cacheAfter = Cache::get(Setting::SETTINGS_CACHE_KEY);

        // Assert cache has been updated
        $this->assertSame(
            'random_new_name',
            $cacheAfter->firstWhere('name', '===', 'app_name')->value
        );
    }

    public function test_settings_are_cached(): void
    {
        settings()->get('app_name');

        $this->assertTrue(Cache::has(Setting::SETTINGS_CACHE_KEY));
    }
}
