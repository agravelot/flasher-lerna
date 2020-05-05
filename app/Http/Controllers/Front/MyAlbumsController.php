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
        $cosplayer = Cosplayer::where('sso_id', auth()->id())->first();
        $albums = $cosplayer ? PublicAlbum::whereHas('cosplayers',
                static function (Builder $query) use ($cosplayer) {
                    $query->where('cosplayers.id', '=', optional($cosplayer)->id);
                }
            )->with('media')->paginate()
        : collect();

        return view('profile.my-albums', compact('albums'));
    }
}
