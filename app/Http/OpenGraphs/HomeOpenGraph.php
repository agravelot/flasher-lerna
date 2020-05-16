<?php

declare(strict_types=1);

namespace App\Http\OpenGraphs;

use App\Http\OpenGraphs\Contracts\ImagesOpenGraphable;
use App\Http\OpenGraphs\Contracts\OpenGraphable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HomeOpenGraph implements OpenGraphable, ImagesOpenGraphable
{
    public function images(): Collection
    {
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
