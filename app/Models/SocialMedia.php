<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SocialMedia extends Model
{
    use ClearsResponseCache;
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
