<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class Policy.
 */
class Policy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
//        if ($user->is_admin || $user->hasRole('creator')) {
        if ($user->is_admin) {
            return true;
        }
    }
}
