<?php

namespace App\Policies;

use App\Tag;
use App\User;

class TagPolicy extends Policy
{
    /**
     * Determine whether the user can view the tag.
     *
     * @param \App\User $authUser
     * @param \App\Tag  $tag
     *
     * @return bool
     */
    public function view(User $authUser, Tag  $tag)
    {
        return true;
    }

    /**
     * Determine whether the user can create tags.
     *
     * @param \App\User $authUser
     *
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->can('create-tag');
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param \App\User $authUser
     * @param \App\Tag  $tag
     *
     * @return bool
     */
    public function update(User $authUser, Tag  $tag)
    {
        return $tag->user_id == $authUser->id || $authUser->can('update-tag');
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param \App\User $authUser
     * @param \App\Tag  $tag
     *
     * @return bool
     */
    public function delete(User $authUser, Tag  $tag)
    {
        return $tag->user_id == $authUser->id || $authUser->can('delete-tag');
    }
}
