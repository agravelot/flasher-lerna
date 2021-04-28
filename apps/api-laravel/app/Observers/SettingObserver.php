<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    public function saved(Setting $setting): void
    {
        Setting::refreshCache();
    }
}
