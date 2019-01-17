<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use App\Abilities\HasParentModelTrait;

class PublishedGoldenBookPost extends GoldenBookPost
{
    use HasParentModelTrait;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->published();
        });
    }
}
