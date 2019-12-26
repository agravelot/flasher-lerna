<?php

namespace App\Http\OpenGraphs;

use App\Http\OpenGraphs\Contracts\OpenGraphable;
use App\Http\OpenGraphs\Contracts\ProfileOpenGraphable;
use App\Models\Cosplayer;
use Illuminate\Support\Str;

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

    public function title(): string
    {
        return $this->cosplayer->name;
    }

    public function description(): string
    {
        return Str::limit($this->cosplayer->description ?? '', 150);
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
