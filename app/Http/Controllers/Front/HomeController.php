<?php

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\PublicAlbum;
use Illuminate\Routing\Controller;
use App\Models\PublishedTestimonial;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(): View
    {
        $albums = PublicAlbum::with('categories')->latest()->take(3)->get();
        $testimonials = PublishedTestimonial::take(3)->get();
        $og = new HomeOpenGraph();

        return view('home', compact('albums', 'testimonials', 'og'));
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
