<?php

namespace App\Criteria;

use App\Models\Album;
use App\Models\GoldenBookPost;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PublicAlbumsCriteria.
 */
class ActiveGoldenBookPostCriteria implements CriteriaInterface
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
        /** @var GoldenBookPost $model */
        return $model->where('active', true);
    }
}
