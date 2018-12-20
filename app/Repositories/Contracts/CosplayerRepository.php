<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

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
