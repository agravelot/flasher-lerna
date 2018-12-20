<?php

namespace App\Repositories;

use App\Criteria\ActiveGoldenBookPostCriteria;
use App\Models\GoldenBookPost;
use App\Repositories\Contracts\GoldenBookRepository;
use Prettus\Repository\Criteria\RequestCriteria;
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
