<?php

namespace App\Policies;

use App\Profile;
use App\User;

class ProfilePolicy extends Policy
{
    /**
     * Determine whether the user can view the profile.
     *
     * @param \App\User    $authUser
     * @param \App\Profile $profile
     *
     * @return bool
     */
    public function view(User $authUser, Profile  $profile)
    {
        return true;
    }

    /**
     * Determine whether the user can create profiles.
     *
     * @param \App\User $authUser
     *
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->can('create-profile');
    }

    /**
     * Determine whether the user can update the profile.
     *
     * @param \App\User    $authUser
     * @param \App\Profile $profile
     *
     * @return bool
     */
    public function update(User $authUser, Profile  $profile)
    {
        return $profile->user_id == $authUser->id || $authUser->can('update-profile');
    }

    /**
     * Determine whether the user can delete the profile.
     *
     * @param \App\User    $authUser
     * @param \App\Profile $profile
     *
     * @return bool
     */
    public function delete(User $authUser, Profile  $profile)
    {
        return $profile->user_id == $authUser->id || $authUser->can('delete-profile');
    }
}
