<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SettingType;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use LogicException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use CastsEnums, InteractsWithMedia;

    public const SETTINGS_CACHE_KEY = 'settings';
    public const SETTING_COLLECTION = 'setting_media';
    public const RESPONSIVE_PICTURES_CONVERSION = 'responsive';

    /**
     * @var array<string>
     */
    protected $fillable = ['name', 'value'];

    /**
     * @var array<string, string>
     */
    protected $enumCasts = [
        'type' => SettingType::class,
    ];

    public static function getCache(): Collection
    {
        return tap(self::with('media')->get(), static function (Collection $settings): void {
            Cache::forever(self::SETTINGS_CACHE_KEY, $settings);
        });
    }

    public static function refreshCache(): Collection
    {
        if (Cache::has(self::SETTINGS_CACHE_KEY)) {
            Cache::forget(self::SETTINGS_CACHE_KEY);
        }

        return self::getCache();
    }

    /**
     * Get the ability to return an Media for media settings.
     *
     * @return Media|string|bool|int|null
     */
    public function getValueAttribute(?string $value)
    {
        if (SettingType::getAliasType($this->type->value) === \Spatie\MediaLibrary\MediaCollections\Models\Media::class) {
            return $this->getFirstMedia(self::SETTING_COLLECTION);
        }

        settype($value, $this->getCastType('value'));

        return $value;
    }

    /**
     * Dynamic type casting for value from type.
     *
     * @param  string  $key
     */
    protected function getCastType($key): string
    {
        if ($key === 'value') {
            if ($this->type === null) {
                throw new LogicException('Setting type cannot be empty');
            }

            return SettingType::getAliasType($this->type->value);
        }

        return parent::getCastType($key);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File|string|bool|int|null $value
     */
    public function setValueAttribute($value): void
    {
        if ($value instanceof \Symfony\Component\HttpFoundation\File\File) {
            [$width, $height] = getimagesize($value->getRealPath());

            $this->addMedia($value)
                ->preservingOriginal()
                ->withCustomProperties(['width' => $width, 'height' => $height])
                ->toMediaCollectionOnCloudDisk(self::SETTING_COLLECTION);

            $this->attributes['value'] = null;

            return;
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Register the collections of this album.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::SETTING_COLLECTION)
            ->singleFile()
            ->acceptsFile(static function (File $file) {
                return Str::startsWith($file->mimeType, 'image/');
            });
    }
}
