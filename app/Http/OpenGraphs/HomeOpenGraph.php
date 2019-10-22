<?php

namespace App\Http\OpenGraphs;

use Illuminate\Support\Collection;
use App\Http\OpenGraphs\Contracts\OpenGraphable;
use App\Http\OpenGraphs\Contracts\ImagesOpenGraphable;

class HomeOpenGraph implements OpenGraphable, ImagesOpenGraphable
{
    public function images(): Collection
    {
        $images[] = asset('/svg/logo.svg');
        $images[] = settings()->get('profile_picture_homepage');

        return collect($images);
    }

    public function title(): string
    {
        return settings()->get('default_page_title');
    }

    public function description(): string
    {
        return settings()->get('seo_description');
    }

    public function type(): string
    {
        return 'website';
    }
}
