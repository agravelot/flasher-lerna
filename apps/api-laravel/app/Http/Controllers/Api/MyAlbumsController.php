<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumIndexResource;
use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class MyAlbumsController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        $cosplayer = Cosplayer::where('sso_id', auth()->id())->first();
        $albums = $cosplayer ? Album::published()->whereHas('cosplayers',
            static function (Builder $query) use ($cosplayer): void {
                $query->where('cosplayers.id', '=', $cosplayer->id);
            }
        )->with('media')->paginate()
            : Container::getInstance()->makeWith(LengthAwarePaginator::class,
                [
                    'items' => [], 'total' => 0, 'perPage' => 10, 'currentPage' => Paginator::resolveCurrentPage(),
                    'options' => ['path' => Paginator::resolveCurrentPath()],
                ]);

        return AlbumIndexResource::collection($albums);
    }
}
