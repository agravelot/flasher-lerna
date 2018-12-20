<?php

namespace App\Criteria;

use App\Models\Album;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PublicAlbumsCriteria.
 */
class PublicAlbumsCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository.
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        /** @var Album $model */
        return $model->whereNotNull('published_at')->whereNull('password');
    }
}
