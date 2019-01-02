<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories;

use App\Models\Cosplayer;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Criteria\RequestCriteria;

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
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
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
