<?php

namespace App\Http\OpenGraphs;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Http\OpenGraphs\Contracts\OpenGraphable;
use App\Http\OpenGraphs\Contracts\ImagesOpenGraphable;

class HomeOpenGraph implements OpenGraphable, ImagesOpenGraphable
{
    public function images(): Collection
    {
        //$logo = asset('/svg/logo.svg');
        $profile = settings()->get('profile_picture_homepage');

        if ($profile === null) {
            return collect();
        }

        return collect([$profile]);
    }

    public function title(): string
    {
        return settings()->get('default_page_title');
    }

    public function description(): string
    {
        return Str::limit(settings()->get('seo_description') ?? '', 150);
    }

    public function type(): string
    {
        return 'website';
    }
}
