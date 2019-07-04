<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Entities;

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
    protected $casts = [
        'value' => 'string', // Dummy cast to force to call getCastType
    ];
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
            return $this->getMedia(self::SETTING_COLLECTION)->get(0);
        }

        settype($value, $this->getCastValueType());

        return $value;
    }

    /**
     * Dynamic type casting for value from type.
     *
     * @return mixed|string
     */
    protected function getCastValueType(): string
    {
        if (empty($this->type)) {
            throw new \LogicException('Setting type cannot be empty');
        }

        if ($this->type->value === 'numeric') {
            return 'integer';
        }

        if ($this->type->value === 'textarea') {
            return 'string';
        }

        return $this->type->value;
    }

    /**
     * @param $value mixed
     */
    public function setValueAttribute($value): void
    {
        if ($value instanceof \Symfony\Component\HttpFoundation\File\File) {
            $this->addMedia($value)
                ->preservingOriginal()
                ->toMediaCollectionOnCloudDisk(self::SETTING_COLLECTION);
            $this->attributes['value'] = null;
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Register the collections of this album.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection(self::SETTING_COLLECTION)
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }
}
