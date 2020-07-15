<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Models\PublicAlbum;
use App\Models\PublishedTestimonial;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $albums = PublicAlbum::with('categories', 'media.model')->latest()->take(3)->get();
        $testimonials = PublishedTestimonial::latest()->take(3)->get();

        return view('home', compact('albums', 'testimonials'));
    }
}
