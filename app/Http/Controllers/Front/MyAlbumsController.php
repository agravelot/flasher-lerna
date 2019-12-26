<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MyAlbumsController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User $user */
        $user = auth()->user();
        $albums = optional(optional($user->cosplayer)->albums())->paginate() ?? collect();

        return view('profile.my-albums', compact('albums'));
    }
}
