<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core;

use InvalidArgumentException;
use Illuminate\Support\Collection;
use Modules\Core\Entities\Setting;
use Spatie\MediaLibrary\Models\Media;

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

    /**
     * Return the setting with the casted type.
     *
     * @param  string  $name
     * @param  null  $default
     *
     * @return string|bool|int|Media
     */
    public function get(string $name, $default = null)
    {
        $setting = $this->settings->firstWhere('name', '===', $name);

        if (! $setting && $default === null) {
            throw new InvalidArgumentException("Unable to find '$name' setting");
        }

        return optional($setting)->value ?: $default;
    }

    public function set(array $setting): Setting
    {
        return tap(Setting::create($setting), function () {
            $this->loadSettings();
        });
    }
}
