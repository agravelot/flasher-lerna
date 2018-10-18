<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CategoryRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface CategoryRepository extends RepositoryInterface
{
    public function saveRelation(Collection $categories, Album $album);
}
