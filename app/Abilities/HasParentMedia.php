<?php

namespace App\Abilities;

// https://github.com/spatie/laravel-medialibrary/issues/1021
trait HasParentMedia
{
    public function getMorphClass()
    {
        return get_parent_class($this);
    }
}
