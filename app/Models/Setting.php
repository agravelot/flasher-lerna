<?php

namespace App\Models;

use LogicException;
use App\Enums\SettingType;
use Spatie\MediaLibrary\File;
use BenSampo\Enum\Traits\CastsEnums;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;

/**
 * Class Setting.
 *
 * @property string $name
 * @property string|null $value
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereValue($value)
 * @mixin \Eloquent
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

    /**
     * @param  Media  $media
     *
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
