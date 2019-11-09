<?php

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\PublicAlbum;
use Illuminate\Routing\Controller;
use App\Models\PublishedTestimonial;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $albums = PublicAlbum::with('categories')->latest()->take(3)->get();
        $testimonials = PublishedTestimonial::latest()->take(3)->get();

        return view('home', compact('albums', 'testimonials'));
    }
}
