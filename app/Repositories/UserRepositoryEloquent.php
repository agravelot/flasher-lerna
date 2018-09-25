<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

    public function create(array $attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return parent::update($attributes, $id);
    }

    /**
     * Count results of repository
     *
     * @param string $columns
     * @return int
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
