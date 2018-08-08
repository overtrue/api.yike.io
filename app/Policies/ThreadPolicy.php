<?php

namespace App\Policies;

use App\Thread;
use App\User;

class ThreadPolicy extends Policy
{
    /**
     * Determine whether the user can view the thread.
     *
     * @param \App\User   $authUser
     * @param \App\Thread $thread
     *
     * @return bool
     */
    public function view(User $authUser, Thread  $thread)
    {
        return true;
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param \App\User $authUser
     *
     * @return bool
     */
    public function create(User $authUser)
    {
        return true;
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param \App\User   $authUser
     * @param \App\Thread $thread
     *
     * @return bool
     */
    public function update(User $authUser, Thread $thread)
    {
        return $thread->user_id == $authUser->id || $authUser->can('update-thread');
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param \App\User   $authUser
     * @param \App\Thread $thread
     *
     * @return bool
     */
    public function delete(User $authUser, Thread  $thread)
    {
        return $thread->user_id == $authUser->id || $authUser->can('delete-thread');
    }
}
