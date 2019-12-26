<?php

namespace App\Models;

use App\Abilities\HasParentMedia;
use App\Abilities\HasParentModel;
use App\Scope\PublicScope;

class PublicAlbum extends Album
{
    use HasParentModel, HasParentMedia;

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new PublicScope());
    }
}
