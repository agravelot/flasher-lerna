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
use App\Models\PublishedGoldenBookPost;

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
        $testimonials = PublishedGoldenBookPost::take(3)->get();

        return view('core::home', compact('albums', 'testimonials'));
    }
}
