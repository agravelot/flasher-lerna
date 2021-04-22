<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class MyAlbumsController extends Controller
{
    public function __invoke(): View
    {
        $cosplayer = Cosplayer::where('sso_id', auth()->id())->first();
        $albums = $cosplayer ? Album::published()->whereHas('cosplayers',
                static function (Builder $query) use ($cosplayer): void {
                    $query->where('cosplayers.id', '=', optional($cosplayer)->id);
                }
            )->with('media')->paginate()
        : collect();

        return view('profile.my-albums', compact('albums'));
    }
}
