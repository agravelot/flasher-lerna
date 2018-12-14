<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use App\Models\Cosplayer;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CosplayerRepository.
 */
interface CosplayerRepository extends RepositoryInterface
{
    public function findBySlug(string $slug): Cosplayer;

    public function findNotLinkedToUser(int $cosplayerId): Cosplayer;

    public function saveRelation(Collection $cosplayers, Album $model): void;
}
