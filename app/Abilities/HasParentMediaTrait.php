<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Abilities;

// https://github.com/spatie/laravel-medialibrary/issues/1021
trait HasParentMediaTrait
{
    public function getMorphClass()
    {
        return get_parent_class($this);
    }
}
