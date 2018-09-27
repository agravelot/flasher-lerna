<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\CosplayerRepository;
use App\Models\Cosplayer;
use App\Validators\CosplayerValidator;

/**
 * Class CosplayerRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CosplayerRepositoryEloquent extends BaseRepository implements CosplayerRepository
{

    /**
     * Find data by field and value
     *
     * @param       $value
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findBySlug($value)
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findBySlug($value)->first();
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
        return Cosplayer::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
