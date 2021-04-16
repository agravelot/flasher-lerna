<?php

declare(strict_types=1);

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
