<?php

namespace App\Models;

use LogicException;
use App\Enums\SettingType;
use Spatie\MediaLibrary\File;
use Illuminate\Support\Collection;
use App\Traits\ClearsResponseCache;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;

class Setting extends Model implements HasMedia
{
    use CastsEnums, HasMediaTrait, ClearsResponseCache;

    private const SETTING_COLLECTION = 'setting_media';
    public const SETTINGS_CACHE_KEY = 'settings';

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

    public static function refreshCache(): Collection
    {
        if (Cache::has(self::SETTINGS_CACHE_KEY)) {
            Cache::forget(self::SETTINGS_CACHE_KEY);
        }

        return tap(self::with('media')->get(), static function (Collection $settings) {
            Cache::forever(self::SETTINGS_CACHE_KEY, $settings);
        });
    }

    /**
     * Get the ability to return an Media for media settings.
     *
     * @param string $value
     *
     * @return Media|string|bool|int|null $value
     */
    public function getValueAttribute($value)
    {
        if (SettingType::getAliasType($this->type) === \App\Models\Media::class) {
            return $this->getFirstMedia(self::SETTING_COLLECTION);
        }

        settype($value, $this->getCastType('value'));

        return $value;
    }

    /**
     * Dynamic type casting for value from type.
     *
     * @param  string  $key
     *
     * @return string
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
            $this->addMedia($value)
                ->preservingOriginal()
                ->withResponsiveImages()
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
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->optimize()
            ->performOnCollections(self::SETTING_COLLECTION);
    }
}
