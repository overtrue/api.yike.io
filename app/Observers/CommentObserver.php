<?php

namespace App\Observers;

use App\Comment;
use App\Thread;

/**
 * Class CommentObserver.
 *
 * @author overtrue <i@overtrue.me>
 */
class CommentObserver
{
    public function saved(Comment $comment)
    {
        $comment->user->refreshCache();

        $this->createActionLog($comment);

        if (\is_callable([$comment->commentable, 'refreshCache'])) {
            $comment->commentable->refreshCache();
        }

        if ($comment->commentable instanceof Thread) {
            $comment->commentable->node->refreshCache();
        }
    }

    public function deleted(Comment $comment)
    {
        $comment->user->refreshCache();

        if (\is_callable([$comment->commentable, 'refreshCache'])) {
            $comment->commentable->refreshCache();
        }
    }

    protected function createActionLog($comment)
    {
        if (!$comment->wasRecentlyCreated) {
            return;
        }

        $targetType = \strtolower(\class_basename($comment->commentable_type));

        \activity('commented.'.$targetType)
            ->performedOn($comment->commentable)
            ->withProperty('content', $comment->content->activity_log_content)
            ->withProperty('comment_id', $comment->id)
            ->log('评论');
    }
}
