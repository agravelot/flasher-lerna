<?php

declare(strict_types=1);

namespace App\Http\OpenGraphs\Contracts;

use Illuminate\Support\Collection;

interface ImagesOpenGraphable
{
    public function images(): Collection;
}
