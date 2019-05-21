<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core;

use Illuminate\Support\Collection;
use Modules\Core\Entities\Setting;

class SettingsManager
{
    /** @var Collection $settings */
    private $settings;

    public function __construct()
    {
        $this->loadSettings();
    }

    private function loadSettings()
    {
        $this->settings = Setting::all();
    }

    public function has(string $name): bool
    {
        return $this->get($name, false) ? true : false;
    }

    public function get(string $name, $default = null): ?string
    {
        $setting = $this->settings->firstWhere('name', '===', $name);

        if (! $setting) {
            throw new \InvalidArgumentException("Unable to find '$name' setting");
        }

        return $setting->value ?: $default;
    }

    public function set(array $setting): Setting
    {
        return tap(Setting::create($setting), function () {
            $this->loadSettings();
        });
    }
}
