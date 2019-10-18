<?php

namespace App\Http\OpenGraphs\Contracts;

use Illuminate\Support\Collection;

interface ImagesOpenGraphable
{
    public function images(): Collection;
}
