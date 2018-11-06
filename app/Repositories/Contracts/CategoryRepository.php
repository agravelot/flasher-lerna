<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use App\Models\Category;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CategoryRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface CategoryRepository extends RepositoryInterface
{
    public function findBySlug(string $slug): Category;

    public function saveRelation(Collection $categories, Album $album): void;
}
