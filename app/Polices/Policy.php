<?php
namespace App\Polices;

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
        if ($user->is_admin || $user->hasRole('creator')) {
            return true;
        }
    }
}
