<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Repositories\Contracts;

use App\Models\Cosplayer;
use App\Models\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 */
interface UserRepository extends RepositoryInterface
{
    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function setCosplayer(User $user, ?Cosplayer $cosplayer);

    public function resetCosplayer(User $user);
}
