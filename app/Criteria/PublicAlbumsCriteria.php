<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

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
     * @param string $model
     */
    public function apply($model, RepositoryInterface $repository)
    {
        /* @var Album $model */
        return $model->whereNotNull('published_at')->whereNull('password');
    }
}
