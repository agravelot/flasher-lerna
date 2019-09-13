<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Spatie\MediaLibrary\Models\Media as MediaBase;

class Media extends MediaBase
{
    use ClearsResponseCache;
}
