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
use App\Abilities\HasTitleAsSlug;
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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Album.
 *
 * @property int                    $id
 * @property string                 $slug
 * @property string                 $title
 * @property string|null            $body
 * @property string|null            $published_at
 * @property string|null            $password
 * @property int                    $user_id
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property Collection|Category[]  $categories
 * @property Collection|Comment[]   $comments
 * @property Collection|Cosplayer[] $cosplayers
 * @property Collection|Media[]     $media
 * @property User                   $user
 * @method static Builder|Album findSimilarSlugs($attribute, $config, $slug)
 * @method static Builder|Album newModelQuery()
 * @method static Builder|Album newQuery()
 * @method static Builder|Album query()
 * @method static Builder|Album whereBody($value)
 * @method static Builder|Album whereCreatedAt($value)
 * @method static Builder|Album whereId($value)
 * @method static Builder|Album wherePassword($value)
 * @method static Builder|Album wherePublishedAt($value)
 * @method static Builder|Album whereSlug($value)
 * @method static Builder|Album whereTitle($value)
 * @method static Builder|Album whereUpdatedAt($value)
 * @method static Builder|Album whereUserId($value)
 * @mixin Eloquent
 * @property int $private
 * @method static Builder|Album wherePrivate($value)
 * @method static Builder|Album public()
 * @property mixed $cover
 * @property-read mixed $cover_responsive
 */
class Album extends Model implements HasMedia
{
    use Sluggable, SluggableScopeHelpers, HasMediaTrait, HasSlugRouteKey, HasTitleAsSlug;

    public const PICTURES_COLLECTION = 'pictures';
    public const RESPONSIVE_PICTURES_CONVERSION = 'responsive';

    protected $with = ['media'];

    protected $dates = [
        'published_at', 'updated_at', 'created_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'published_at',
        'user_id',
        'private',
    ];

    public function getCoverAttribute()
    {
        return $this->getFirstMedia(Album::PICTURES_COLLECTION);
    }

    public function getCoverResponsiveAttribute()
    {
        return optional($this->getFirstMedia(Album::PICTURES_COLLECTION), function (Media $media) {
            return $media(Album::RESPONSIVE_PICTURES_CONVERSION);
        });
    }

    /**
     * Scope for public albums.
     *
     * @param Builder $query
     */
    public function scopePublic(Builder $query)
    {
        $query->whereNotNull('published_at')
            ->where('private', false);
    }

    /**
     * Return if the album is public.
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->isPublished() && $this->isPasswordLess();
    }

    /**
     * Return if the album is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->published_at !== null;
    }

    /**
     * Return if the album is password less.
     *
     * @return bool
     */
    public function isPasswordLess()
    {
        return $this->private == false;
    }

    /**
     * Return the related user to this album.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all the comments of this albums.
     *
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Return all the categories of this album.
     *
     * @return MorphToMany
     */
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Return all the cosplayers of this album.
     *
     * @return BelongsToMany
     */
    public function cosplayers()
    {
        return $this->belongsToMany(Cosplayer::class);
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param string|UploadedFile $media
     *
     * @return Media
     */
    public function addPicture($media)
    {
        $uuid = Str::uuid();

        // TODO Fix extension
        $name = "{$this->slug}_{$uuid}.{$media->getClientOriginalExtension()}";

        return $this->addMedia($media)
            ->usingName($name)
            ->usingFileName($name)
            ->preservingOriginal()
            ->toMediaCollectionOnCloudDisk(Album::PICTURES_COLLECTION);
    }

    /**
     * Register the collections of this album.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection(Album::PICTURES_COLLECTION)
            ->acceptsFile(function (File $file) {
                return mb_strpos($file->mimeType, 'image/') === 0;
            });
    }

    /**
     * @param Media|null $media
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion(Album::RESPONSIVE_PICTURES_CONVERSION)
            ->sharpen(10)
            ->optimize()
            ->withResponsiveImages()
            ->performOnCollections(Album::PICTURES_COLLECTION);

        $this->addMediaConversion('thumb')
            ->width(400)
            ->sharpen(8)
            ->optimize()
            ->performOnCollections(Album::PICTURES_COLLECTION);
    }
}
