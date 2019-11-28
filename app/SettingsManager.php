<?php

namespace App;

use App\Models\Setting;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Models\Media;

class SettingsManager
{
    private Collection $settings;

    public function __construct()
    {
        $this->settings = Setting::refreshCache();
    }

    public function has(string $name): bool
    {
        return (bool) $this->get($name, false);
    }

    /**
     * Return the setting with the casted type.
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

    public function set(string $name, $value): Setting
    {
        return tap(Setting::updateOrCreate(['name' => $name], [
            'name' => $name,
            'value' => $value,
        ]), static function () {
            Setting::refreshCache();
        });
    }
}
