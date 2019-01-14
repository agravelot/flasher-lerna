<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use App\Abilities\HasParentMediaTrait;
use App\Abilities\HasParentModelTrait;
use App\Scope\PublicScope;

class PublicAlbum extends Album
{
    use HasParentModelTrait, HasParentMediaTrait;

    protected $table = 'albums';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublicScope());
    }
}
