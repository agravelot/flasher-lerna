<?php

namespace App\Abilities;

trait HasSlugRouteKey
{
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
