<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CosplayerRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface CosplayerRepository extends RepositoryInterface
{
    public function findNotLinkedToUser($cosplayerId);

    public function saveRelation(Collection $cosplayers, Album $model);
}
