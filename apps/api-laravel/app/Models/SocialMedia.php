<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'social_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'icon', 'url', 'color', 'active'];

    /**
     * Scope for active social medias.
     */
    public function scopeActive(): Builder
    {
        return $this->where('active', true);
    }
}
