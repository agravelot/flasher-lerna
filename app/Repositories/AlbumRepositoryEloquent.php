<?php

namespace App\Repositories;

use App\Models\Album;
use App\Repositories\Contracts\AlbumRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AlbumRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AlbumRepositoryEloquent extends BaseRepository implements AlbumRepository
{

    public function create(array $attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['user_id'] = Auth::user()->id;
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return parent::update($attributes, $id);
    }

    /**
     * Find data by field and value
     *
     * @param       $slug
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findBySlug($slug)
    {
//        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findBySlug($slug);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Count results of repository
     *
     * @param string $columns
     * @return int
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function count($columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();
        $result = $this->model->count($columns);
        $this->resetModel();
        $this->resetScope();
        return $result;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Album::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
