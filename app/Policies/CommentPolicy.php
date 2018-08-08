<?php

namespace App\Policies;

use App\Comment;
use App\User;

class CommentPolicy extends Policy
{
    /**
     * Determine whether the user can view the comment.
     *
     * @param \App\User    $authUser
     * @param \App\Comment $comment
     *
     * @return bool
     */
    public function view(User $authUser, Comment  $comment)
    {
        return true;
    }

    /**
     * Determine whether the user can create comments.
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
     * Determine whether the user can update the comment.
     *
     * @param \App\User    $authUser
     * @param \App\Comment $comment
     *
     * @return bool
     */
    public function update(User $authUser, Comment  $comment)
    {
        return $comment->user_id == $authUser->id || $authUser->can('update-comment');
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param \App\User    $authUser
     * @param \App\Comment $comment
     *
     * @return bool
     */
    public function delete(User $authUser, Comment  $comment)
    {
        return $comment->user_id == $authUser->id || $authUser->can('delete-comment');
    }
}
