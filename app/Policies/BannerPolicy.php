<?php

namespace App\Policies;

use App\Banner;
use App\User;

class BannerPolicy extends Policy
{
    /**
     * Determine whether the user can view the banner.
     *
     * @param \App\User   $authUser
     * @param \App\Banner $banner
     *
     * @return bool
     */
    public function view(User $authUser, Banner  $banner)
    {
        return true;
    }

    /**
     * Determine whether the user can create banners.
     *
     * @param \App\User $authUser
     *
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->can('create-banner');
    }

    /**
     * Determine whether the user can update the banner.
     *
     * @param \App\User   $authUser
     * @param \App\Banner $banner
     *
     * @return bool
     */
    public function update(User $authUser, Banner  $banner)
    {
        return $banner->user_id == $authUser->id || $authUser->can('update-banner');
    }

    /**
     * Determine whether the user can delete the banner.
     *
     * @param \App\User   $authUser
     * @param \App\Banner $banner
     *
     * @return bool
     */
    public function delete(User $authUser, Banner  $banner)
    {
        return $banner->user_id == $authUser->id || $authUser->can('delete-banner');
    }
}
