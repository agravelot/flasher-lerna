<?php

namespace App\Jobs;

use Spatie\MediaLibrary\Jobs\GenerateResponsiveImages as GenerateResponsiveImagesBase;

class GenerateResponsiveImages extends GenerateResponsiveImagesBase
{
    public int $timeout = 30 * 60; // 30 minutes

    public function retryAfter(): int
    {
        return $this->timeout + 10;
    }
}
