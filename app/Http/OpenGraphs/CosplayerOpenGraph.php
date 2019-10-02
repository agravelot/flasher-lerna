<?php

namespace App\Http\OpenGraphs;

use App\Models\Cosplayer;
use App\Models\Contracts\OpenGraphable;
use App\Models\Contracts\ProfileOpenGraphable;

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
        return $this->cosplayer->description;
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
