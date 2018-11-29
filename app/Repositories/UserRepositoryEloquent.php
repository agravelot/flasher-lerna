<?php

namespace App\Repositories;

use App\Models\Cosplayer;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function create(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        return parent::update($attributes, $id);
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
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param User           $user
     * @param Cosplayer|null $cosplayer
     *
     * @throws \Exception
     */
    public function setCosplayer(User $user, ?Cosplayer $cosplayer)
    {
        if (isset($cosplayer)) {
            // We need to perform verification to avoid unwanted modification
            if ($cosplayer->user()->exists()) {
                throw new \Exception('Cosplayer is already linked too an user');
            }
            $this->resetCosplayer($user);
            $user->cosplayer()->save($cosplayer);
        } else {
            $this->resetCosplayer($user);
        }
    }

    public function resetCosplayer(User $user)
    {
        if (isset($user->cosplayer)) {
            $user->cosplayer->user()->dissociate();
            $user->cosplayer->save();
        }
    }
}
