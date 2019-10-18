<?php

namespace App;

use App\Models\Setting;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Models\Media;

class SettingsManager
{
    /** @var Collection $settings */
    private $settings;

    public function __construct()
    {
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $this->settings = Setting::with('media')->get();
    }

    public function has(string $name): bool
    {
        return $this->get($name, false) ? true : false;
    }

    /**
     * Return the setting with the casted type.
     *
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
