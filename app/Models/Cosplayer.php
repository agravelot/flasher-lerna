<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use Spatie\MediaLibrary\File;
use Illuminate\Support\Carbon;
use App\Abilities\HasNameAsSlug;
use Illuminate\Http\UploadedFile;
use App\Abilities\HasSlugRouteKey;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Cosplayer.
 *
 * @property int                   $id
 * @property string                $name
 * @property string                $slug
 * @property string|null           $description
 * @property string|null           $picture
 * @property int|null              $user_id
 * @property Carbon|null           $created_at
 * @property Carbon|null           $updated_at
 * @property Collection|Album[]    $albums
 * @property Collection|Category[] $categories
 * @property Collection|Media[]    $media
 * @property User|null             $user
 * @method static Builder|Cosplayer findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|Cosplayer newModelQuery()
 * @method static Builder|Cosplayer newQuery()
 * @method static Builder|Cosplayer query()
 * @method static Builder|Cosplayer whereCreatedAt($value)
 * @method static Builder|Cosplayer whereDescription($value)
 * @method static Builder|Cosplayer whereId($value)
 * @method static Builder|Cosplayer whereName($value)
 * @method static Builder|Cosplayer wherePicture($value)
 * @method static Builder|Cosplayer whereSlug($value)
 * @method static Builder|Cosplayer whereUpdatedAt($value)
 * @method static Builder|Cosplayer whereUserId($value)
 * @mixin Eloquent
 * @property Collection|PublicAlbum[] $publicAlbums
 * @property mixed                    $initial
 * @property mixed                    $avatar
 */
class Cosplayer extends Model implements HasMedia
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait, HasSlugRouteKey, HasNameAsSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'slug', 'user_id'];

    /**
     * Return the initials of the cosplayer.
     *
     * @return bool|false|mixed|string|string[]|null
     */
    public function getInitialAttribute()
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMedia('avatar');
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param UploadedFile|null $media
     */
    public function setAvatarAttribute($media)
    {
        if (! $media && $this->avatar) {
            $this->avatar->delete();
        }

        if (! $media) {
            return;
        }

        $name = "{$this->slug}.{$media->clientExtension()}";

        $this->addMedia($media)
            ->usingFileName($name)
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollectionOnCloudDisk('avatar');
    }

    /**
     * Return the linked user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the albums posted by this user.
     *
     * @return BelongsToMany
     */
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class);
    }

    /**
     * Return the public albums posted by this user.
     *
     * @return BelongsToMany
     */
    public function publicAlbums(): BelongsToMany
    {
        return $this->belongsToMany(PublicAlbum::class, 'album_cosplayer');
    }

    /**
     * Return the categories related to this user.
     *
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Defining the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->acceptsFile(static function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            })
            ->singleFile();
    }

    /**
     * Register the media conversions.
     *
     * @param Media|null $media
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 96, 96)
            ->sharpen(7)
            ->performOnCollections('avatar');
    }
}
