<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumIndexResource;
use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MyAlbumsController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        dump(auth()->id());
        $cosplayer = Cosplayer::where('sso_id', auth()->id())->first();
        $albums = $cosplayer ? Album::published()->whereHas('cosplayers',
                static function (Builder $query) use ($cosplayer): void {
                    $query->where('cosplayers.id', '=', optional($cosplayer)->id);
                }
            )->with('media')->paginate()
        : collect();

        return AlbumIndexResource::collection($albums);
    }
}
