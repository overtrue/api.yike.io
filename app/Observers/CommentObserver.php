<?php


namespace App\Observers;
use App\Comment;


/**
 * Class CommentObserver
 *
 * @author overtrue <i@overtrue.me>
 */
class CommentObserver
{
    public function created(Comment $comment)
    {
        $comment->commentable->afterCommentCreated($comment);
    }

    public function deleted(Comment $comment)
    {

    }
}