<?php

namespace App\Contracts;

use App\Comment;

/**
 * Interface Commentable.
 *
 * @author overtrue <i@overtrue.me>
 */
interface Commentable
{
    /**
     * @param \App\Comment $lastComment
     *
     * @return mixed
     */
    public function afterCommentCreated(Comment $lastComment);
}
