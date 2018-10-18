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
 *
 * @package namespace App\Repositories;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
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
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findBySlug($slug);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function saveRelation(Collection $categories, Album $album)
    {
        $album->categories()->sync($categories);
    }
}
