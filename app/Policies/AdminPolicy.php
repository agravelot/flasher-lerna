<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy extends Policy
{
    use HandlesAuthorization;   

   public function dashboard(User $user) {
       return false;
   }
}
