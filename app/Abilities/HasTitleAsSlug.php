<?php

declare(strict_types=1);

namespace App\Abilities;

trait HasTitleAsSlug
{
    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
}
