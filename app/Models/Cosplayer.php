<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\File;
use Illuminate\Support\Carbon;
use App\Abilities\HasNameAsSlug;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
 *
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
 *
 * @property Collection|PublicAlbum[] $publicAlbums
 * @property mixed                    $initial
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

    /**
     * Return the linked user.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the albums posted by this user.
     *
     * @return BelongsToMany
     */
    public function albums()
    {
        return $this->belongsToMany(Album::class);
    }

    /**
     * Return the public albums posted by this user.
     *
     * @return BelongsToMany
     */
    public function publicAlbums()
    {
        return $this->belongsToMany(PublicAlbum::class, 'album_cosplayer');
    }

    /**
     * Return the categories related to this user.
     *
     * @return MorphToMany
     */
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Add media to 'pictures' collection.
     *
     * @param string|UploadedFile $media
     *
     * @return Media
     */
    public function setAvatar($media)
    {
        $uuid = Str::uuid();
        $name = "{$this->slug}_{$uuid}.{$media->getClientOriginalExtension()}";

        return $this->addMedia($media)
            ->usingFileName($name)
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollection('avatar');
    }

    /**
     * Defining the media collections.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }

    /**
     * Register the media conversions.
     *
     * @param Media|null $media
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 96, 96)
            ->sharpen(7)
            ->performOnCollections('avatar');
    }
}
