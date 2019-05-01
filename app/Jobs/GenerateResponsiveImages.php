<?php

namespace App\Jobs;

use Spatie\MediaLibrary\Jobs\GenerateResponsiveImages as GenerateResponsiveImagesBase;

class GenerateResponsiveImages extends GenerateResponsiveImagesBase
{
    public $timeout = 30 * 60; // 30 minutes
}
