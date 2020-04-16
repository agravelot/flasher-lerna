<?php

namespace App\Jobs;

use Spatie\MediaLibrary\ResponsiveImages\Jobs\GenerateResponsiveImagesJob;

class GenerateResponsiveImages extends GenerateResponsiveImagesJob
{
    public int $timeout = 30 * 60; // 30 minutes

    public function retryAfter(): int
    {
        return $this->timeout + 10;
    }
}
