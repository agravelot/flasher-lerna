<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ContactRepositoryEloquent.
 */
class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Contact::class;
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
    public function count($columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();
        $result = $this->model->count($columns);
        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
