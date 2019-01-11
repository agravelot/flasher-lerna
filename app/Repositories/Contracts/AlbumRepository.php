<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories\Contracts;

use App\Models\Album;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AlbumRepository.
 */
interface AlbumRepository extends RepositoryInterface
{
    public function findBySlug(string $slug): Album;

    public function latestWithPagination();
}
