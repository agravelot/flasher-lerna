<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AlbumRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface AlbumRepository extends RepositoryInterface
{
    public function findBySlug(string $slug): Album;

    public function count($columns = '*'): int;
}
