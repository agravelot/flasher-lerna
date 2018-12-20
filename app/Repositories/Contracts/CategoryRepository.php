<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories\Contracts;

use App\Models\Album;
use App\Models\Category;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CategoryRepository.
 */
interface CategoryRepository extends RepositoryInterface
{
    public function findBySlug(string $slug): Category;

    public function saveRelation(Collection $categories, Album $album): void;
}
