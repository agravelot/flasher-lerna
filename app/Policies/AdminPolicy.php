<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy extends Policy
{
   public function dashboard(User $user) {
       return false;
   }
}
