<?php

namespace App\Jobs;

use Spatie\MediaLibrary\Jobs\PerformConversions as PerformConversionsBase;

class PerformConversions extends PerformConversionsBase
{
    public int $timeout = 30 * 60; // 30 minutes

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    public function retryAfter(): int
    {
        return $this->timeout + 10;
    }
}
