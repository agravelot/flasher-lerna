<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class MyAlbumsController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User $user */
        $user = auth()->user();
        $albums = $user->cosplayer->albums ?? collect();

        return view('profile.my-albums', compact('albums'));
    }
}
