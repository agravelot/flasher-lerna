<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;

abstract class BaseRepository extends PrettusBaseRepository
{
    abstract public function model();

    /**
     * Count results of repository.
     *
     * @param string $columns
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function count($columns = '*'): int
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->count($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }
}
