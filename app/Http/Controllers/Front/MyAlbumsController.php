<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PublicAlbum;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class MyAlbumsController extends Controller
{
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();
        $albums = $user->cosplayer ? PublicAlbum::whereHas('cosplayers',
                static function (Builder $query) use ($user) {
                    $query->where('cosplayers.id', '=', $user->cosplayer->id);
                }
            )->with('media')->paginate()
        : collect();

        return view('profile.my-albums', compact('albums'));
    }
}
