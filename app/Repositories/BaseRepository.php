<?php

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
     *
     * @return int
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
