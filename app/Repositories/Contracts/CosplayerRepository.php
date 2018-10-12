<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CosplayerRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface CosplayerRepository extends RepositoryInterface
{
    public function findNotLinkedToUser($cosplayerId);
}
