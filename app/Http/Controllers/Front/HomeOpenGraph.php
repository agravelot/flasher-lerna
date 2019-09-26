<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Collection;
use App\Models\Contracts\OpenGraphable;
use App\Models\Contracts\ImagesOpenGraphable;

class HomeOpenGraph implements OpenGraphable, ImagesOpenGraphable
{
    public function images(): Collection
    {
        $image = settings()->get('profile_picture_homepage');

        if ($image === null) {
            return collect();
        }

        return collect([$image]);
    }

    public function title(): string
    {
        return settings()->get('default_page_title');
    }

    public function description(): string
    {
        return '';
    }

    public function type(): string
    {
        return 'website';
    }
}
