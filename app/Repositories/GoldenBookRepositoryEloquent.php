<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories;

use App\Criteria\ActiveGoldenBookPostCriteria;
use App\Models\GoldenBookPost;
use App\Repositories\Contracts\GoldenBookRepository;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AlbumRepositoryEloquent.
 */
class GoldenBookRepositoryEloquent extends BaseRepository implements GoldenBookRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return GoldenBookPost::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(ActiveGoldenBookPostCriteria::class));
    }
}
