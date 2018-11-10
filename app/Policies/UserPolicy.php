<?php

namespace App\Policies;

use App\User;

class UserPolicy extends Policy
{
    /**
     * Determine whether the user can view the user.
     *
     * @param \App\User $authUser
     * @param \App\User $user
     *
     * @return bool
     */
    public function view(User $authUser, User  $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param \App\User $authUser
     * @param \App\User $user
     *
     * @return bool
     */
    public function update(User $authUser, User  $user)
    {
        return $user->id == $authUser->id || $authUser->can('update-user');
    }
}
