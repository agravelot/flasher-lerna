<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Jobs;

use Spatie\MediaLibrary\Jobs\GenerateResponsiveImages as GenerateResponsiveImagesBase;

class GenerateResponsiveImages extends GenerateResponsiveImagesBase
{
    public $timeout = 5 * 60; // 5 minutes
}
