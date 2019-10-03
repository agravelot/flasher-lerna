<?php

namespace App\Jobs;

use Spatie\MediaLibrary\Jobs\PerformConversions as PerformConversionsBase;

class PerformConversions extends PerformConversionsBase
{
    public $timeout = 30 * 60; // 30 minutes

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    public function retryAfter()
    {
        return $this->timeout + 10;
    }
}
