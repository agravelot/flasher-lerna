<?php

namespace App\Models;

use App\Scope\PublicScope;
use App\Abilities\HasParentMedia;
use App\Abilities\HasParentModel;

class PublicAlbum extends Album
{
    use HasParentModel, HasParentMedia;

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new PublicScope());
    }
}
