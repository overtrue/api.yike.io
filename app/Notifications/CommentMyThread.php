<?php

namespace App\Notifications;

use App\Comment;
use App\Mail\NewComment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentMyThread extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param \App\Comment
     * @param \App\User
     */
    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \App\Mail\NewComment
     */
    public function toMail($notifiable)
    {
        return (new NewComment($this->comment))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'avatar' => $this->user->avatar,
            'comment_id' => $this->comment->id,
            'content' => $this->comment->content->activity_log_content,
            'thread_title' => $this->comment->commentable->title,
            'thread_id' => $this->comment->commentable->id,
        ];
    }
}
