<?php

namespace App\Http\OpenGraphs;

use App\Models\Album;
use App\Models\Contracts\ProfileOpenGraphable;
use App\Models\Cosplayer;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Contracts\OpenGraphable;
use App\Models\Contracts\ImagesOpenGraphable;
use App\Models\Contracts\ArticleOpenGraphable;

class CosplayerOpenGraph implements OpenGraphable, ProfileOpenGraphable
{
    /**
     * @var Cosplayer
     */
    private $cosplayer;

    public function __construct(Cosplayer $cosplayer)
    {
        $this->cosplayer = $cosplayer;
    }

    public function type(): string
    {
        return 'profile';
    }

    public function username(): string
    {
        return $this->cosplayer->name;
    }

    public function firstName(): string
    {
        return '';
    }

    public function lastName(): string
    {
        return '';
    }

    public function gender(): string
    {
        return '';
    }
}
