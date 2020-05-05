<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cosplayer;
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
        $cosplayer = Cosplayer::where('sso_id', $user->sub)->first();
        $albums = $cosplayer ? PublicAlbum::whereHas('cosplayers',
                static function (Builder $query) use ($cosplayer) {
                    $query->where('cosplayers.id', '=', optional($cosplayer)->id);
                }
            )->with('media')->paginate()
        : collect();

        return view('profile.my-albums', compact('albums'));
    }
}
