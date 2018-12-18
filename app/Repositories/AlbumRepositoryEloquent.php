<?php

namespace App\Repositories;

use App\Criteria\PublicAlbumsCriteria;
use App\Models\Album;
use App\Repositories\Contracts\AlbumRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Eloquent\BaseRepository;

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
        $result = $this->model->with('cosplayers.media')->whereSlug($slug)->firstOrFail();
        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * Count results of repository.
     *
     * @param string $columns
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return int
     */
    public function count($columns = '*'): int
    {
        $this->applyCriteria();
        $this->applyScope();
        $result = $this->model->count($columns);
        $this->resetModel();
        $this->resetScope();

        return $result;
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
