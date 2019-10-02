<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Spatie\MediaLibrary\Models\Media as MediaBase;

class Media extends MediaBase
{
    use ClearsResponseCache;
}
