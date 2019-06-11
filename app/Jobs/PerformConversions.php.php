<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Jobs;

use Spatie\MediaLibrary\Jobs\PerformConversions as PerformConversionsBase;

class PerformConversions extends PerformConversionsBase
{
    public $timeout = 30 * 60; // 30 minutes
}
