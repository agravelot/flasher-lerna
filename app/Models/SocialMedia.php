<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use ClearsResponseCache;

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
