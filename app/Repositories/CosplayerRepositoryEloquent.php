<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Repositories\Contracts\CosplayerRepository;
use Illuminate\Support\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CosplayerRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CosplayerRepositoryEloquent extends BaseRepository implements CosplayerRepository
{
    /**
     * @param $cosplayerId
     * @return mixed
     * @throws \Exception
     */
    public function findNotLinkedToUser(int $cosplayerId): Cosplayer
    {
        $cosplayer = $this->find($cosplayerId);
        if ($cosplayer->user()->exists()) {
            throw new \Exception('Cosplayer is already linked too an user');
        }
        return $cosplayer;
    }

    /**
     * Find data by field and value
     *
     * @param string $slug
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findBySlug(string $slug): Cosplayer
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereSlug($slug)->first();
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

    public function saveRelation(Collection $cosplayers, Album $model): void
    {
        $model->cosplayers()->sync($cosplayers);
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
