<?php

declare(strict_types=1);

namespace App;

use App\Models\Setting;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SettingsManager
{
    private ?Collection $settings;

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
        if ($this->settings === null) {
            Setting::getCache();
        }

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
        ]), static function (): void {
            Setting::refreshCache();
        });
    }
}
