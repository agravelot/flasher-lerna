<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AlbumRepository.
 */
interface AlbumRepository extends RepositoryInterface
{
    public function findBySlug(string $slug): Album;

    public function count($columns = '*'): int;
}
