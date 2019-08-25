<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\View\View;
use App\Models\PublicAlbum;
use Illuminate\Routing\Controller;
use App\Models\PublishedTestimonial;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function __invoke(): View
    {
        $albums = PublicAlbum::with('categories')->latest()->take(3)->get();
        $testimonials = PublishedTestimonial::take(3)->get();
        $og = new HomeOpenGraph();

        return view('core::home', compact('albums', 'testimonials', 'og'));
    }
}

class HomeOpenGraph implements \App\Models\Contracts\OpenGraphable, \App\Models\Contracts\ImagesOpenGraphable
{
    public function images() : \Illuminate\Support\Collection
    {
        $image = settings()->get('profile_picture_homepage');

        if ($image === null) {
            return collect();
        }

        return collect([$image]);
    }

    public function title() : string
    {
        return settings()->get('default_page_title');
    }

    public function description() : string
    {
        return '';
    }

    public function type() : string
    {
        return 'website';
    }
}
