<?php

declare(strict_types=1);

namespace App\Models;

use App\Abilities\HasNameAsSlug;
use App\Abilities\HasSlugRouteKey;
use App\Adapters\Keycloak\UserRepresentation;
use App\Facades\Keycloak;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Cosplayer extends Model implements HasMedia
{
    use Sluggable,
        SluggableScopeHelpers,
        InteractsWithMedia,
        HasSlugRouteKey,
        HasNameAsSlug,
        Searchable;

    public const RESPONSIVE_PICTURES_CONVERSION = 'responsive';
    const AVATAR_COLLECTION = 'avatar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'description', 'slug', 'user_id', 'sso_id'];

    /**
     * Return the initials of the cosplayer.
     */
    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    public function getAvatarAttribute(): ?Media
    {
        return $this->getFirstMedia(self::AVATAR_COLLECTION);
    }

    public function getAvatarResponsiveAttribute(): ?HtmlableMedia
    {
        return optional($this->avatar, static function (Media $media) {
            return $media(self::RESPONSIVE_PICTURES_CONVERSION);
        });
    }

    /**
     * Add media to Album::PICTURES_COLLECTION collection.
     */
    public function setAvatarAttribute(?UploadedFile $media): void
    {
        if (! $media) {
            optional($this->avatar)->delete();

            return;
        }

        [$width, $height] = getimagesize($media->getRealPath());

        $this->addMedia($media)
            ->usingFileName("{$this->slug}.{$media->clientExtension()}")
            ->preservingOriginal()
            ->withCustomProperties(['width' => $width, 'height' => $height])
            ->toMediaCollectionOnCloudDisk(self::AVATAR_COLLECTION);
    }

    /**
     * Return the linked user.
     */
    public function user(): ?UserRepresentation
    {
        return $this->sso_id ? Keycloak::users()->find($this->sso_id) : null;
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
        $this->addMediaCollection(self::AVATAR_COLLECTION)
            ->acceptsFile(static function (File $file) {
                return Str::startsWith($file->mimeType, 'image/');
            })
            ->singleFile();
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
            'avatar' => optional($this->avatar)->getUrl(),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }
}
