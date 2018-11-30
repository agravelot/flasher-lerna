<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepository;
use Illuminate\Support\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CategoryRepositoryEloquent.
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Find data by field and value.
     *
     * @param $slug
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return mixed
     */
    public function findBySlug(string $slug): Category
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->with('albums.media', 'albums.categories')->whereSlug($slug)->first();
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function saveRelation(Collection $categories, Album $album): void
    {
        $album->categories()->sync($categories);
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
