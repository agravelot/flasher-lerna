<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\PublicAlbum;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class MyAlbumsController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User $user */
        $user = auth()->user();
        $albums = PublicAlbum::whereHas('cosplayers',
                function(Builder $query) use ($user) {
                    $query->where('cosplayers.id', '=', $user->cosplayer->id);
                }
            )->with('media')->paginate();

        return view('profile.my-albums', compact('albums'));
    }
}
