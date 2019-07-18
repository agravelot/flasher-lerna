<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Entities;

use LogicException;
use Spatie\MediaLibrary\File;
use Modules\Core\Enums\SettingType;
use BenSampo\Enum\Traits\CastsEnums;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Setting.
 */
class Setting extends Model implements HasMedia
{
    use CastsEnums, HasMediaTrait;

    const SETTING_COLLECTION = 'setting_media';

    protected $fillable = ['name', 'value'];
    protected $enumCasts = [
        'type' => SettingType::class,
    ];

    /**
     * Get the ability to return an Media for media settings.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        if (SettingType::getAliasType($this->type) === Media::class) {
            return $this->getFirstMedia(self::SETTING_COLLECTION);
        }

        settype($value, $this->getCastType('value'));

        return $value;
    }

    /**
     * Dynamic type casting for value from type.
     *
     * @return mixed|string
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
     * @param $value void
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
}
