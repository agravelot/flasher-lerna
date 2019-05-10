<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core;

use Modules\Core\Entities\Setting;

class SettingsManager
{
    public function has(string $name): bool
    {
        return Setting::find($name) ? true : false;
    }

    public function get(string $name, $default = null)
    {
        return Setting::findOrFail($name)->value ?: $default;
    }

    public function set(array $setting): Setting
    {
        return Setting::create($setting);
    }
}
