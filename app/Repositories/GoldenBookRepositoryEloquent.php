<?php

namespace App\Repositories;

use App\Models\GoldenBookPost;
use App\Repositories\Contracts\GoldenBookRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AlbumRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class GoldenBookRepositoryEloquent extends BaseRepository implements GoldenBookRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GoldenBookPost::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
