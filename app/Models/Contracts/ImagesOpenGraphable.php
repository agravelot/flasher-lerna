<?php

namespace App\Models\Contracts;

use Illuminate\Support\Collection;

interface ImagesOpenGraphable
{
    public function images(): Collection;
}
