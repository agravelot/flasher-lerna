<?php

namespace App\Repositories\Contracts;

use App\Models\Album;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PictureRepository.
 *
 * @package namespace App\Repositories\Contracts;
 */
interface PictureRepository extends RepositoryInterface
{
    public function createForAlbum(array $attributes, Album $album);
}
