<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Repositories\Contracts\CosplayerRepository;
use Illuminate\Support\Collection;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class CosplayerRepositoryEloquent.
 */
class CosplayerRepositoryEloquent extends BaseRepository implements CosplayerRepository
{
    /**
     * @param $cosplayerId
     *
     * @throws \Exception
     *
     * @return mixed
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
     * Find data by field and value.
     *
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return mixed
     */
    public function findBySlug(string $slug): Cosplayer
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereSlug($slug)->first();
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function saveRelation(Collection $cosplayers, Album $model): void
    {
        $model->cosplayers()->sync($cosplayers);
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Cosplayer::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
