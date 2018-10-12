<?php

namespace App\Repositories\Contracts;

use App\Models\Cosplayer;
use App\Models\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories;
 */
interface UserRepository extends RepositoryInterface
{
    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function setCosplayer(User $user, ?Cosplayer $cosplayer);

    public function resetCosplayer(User $user);
}
