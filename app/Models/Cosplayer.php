<?php

namespace App\Models;

use App\Abilities\HasNameAsSlug;
use App\Abilities\HasSlugRouteKey;
use App\Traits\ClearsResponseCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Cosplayer extends Model implements HasMedia
{
    use Sluggable,
        SluggableScopeHelpers,
        HasMediaTrait,
        HasSlugRouteKey,
        HasNameAsSlug,
        ClearsResponseCache,
        Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'description', 'slug', 'user_id'];

    /**
     * Return the initials of the cosplayer.
     */
    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    public function getAvatarAttribute(): ?Media
    {
        return $this->getFirstMedia('avatar');
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     *
     * @param  UploadedFile|null  $media
     */
    public function setAvatarAttribute($media): void
    {
        if (! $media) {
            optional($this->avatar)->delete();

            return;
        }

        $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->clientExtension()}")
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollectionOnCloudDisk('avatar');
    }

    /**
     * Return the linked user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the albums posted by this user.
     */
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class);
    }

    /**
     * Return the public albums posted by this user.
     */
    public function publicAlbums(): BelongsToMany
    {
        return $this->belongsToMany(PublicAlbum::class, 'album_cosplayer');
    }

    /**
     * Return the categories related to this user.
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
                return Str::startsWith($file->mimeType, 'image/');
            })
            ->singleFile();
    }

    /**
     * Register the media conversions.
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 96, 96)
            ->performOnCollections('avatar');
    }

    public function searchableAs(): string
    {
        return 'cosplayers-'.config('app.env');
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'thumb' => optional($this->avatar)->getUrl('thumb'),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }
}
