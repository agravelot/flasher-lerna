<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepository;
use Illuminate\Support\Collection;
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
     * @return mixed
     */
    public function findBySlug(string $slug): Category
    {
        /* @var Category $model */
        return $this->model->with([
            'albums.media',
            'albums.categories',
            'albums' => function ($query) {
                $query->whereNotNull('published_at')->whereNull('password');
            }, ])
            ->whereSlug($slug)
            ->firstOrFail();
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
}
