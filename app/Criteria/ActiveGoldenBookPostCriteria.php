<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Criteria;

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
     * @param string $model
     *
     * @return GoldenBookPost
     */
    public function apply($model, RepositoryInterface $repository)
    {
        /* @var GoldenBookPost $model */
        return $model->where('active', true);
    }
}
