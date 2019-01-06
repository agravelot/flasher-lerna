<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories;

use App\Criteria\PublicAlbumsCriteria;
use App\Models\Album;
use App\Repositories\Contracts\AlbumRepository;

/**
 * Class AlbumRepositoryEloquent.
 */
class AlbumRepositoryEloquent extends BaseRepository implements AlbumRepository
{
    public function latestWithPagination()
    {
        return parent::with(['media', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    /**
     * Find data by field and value.
     *
     * @param $slug
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return mixed
     */
    public function findBySlug(string $slug): Album
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->with('cosplayers.media')->whereSlug($slug)->firstOrFail();
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Album::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
        $this->pushCriteria(PublicAlbumsCriteria::class);
    }
}
