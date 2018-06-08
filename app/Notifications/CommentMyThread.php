<?php

namespace App\Notifications;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'username' => $this->user->name,
            'avatar' => $this->user->avatar,
            'comment_id' => $this->comment->id,
            'thread_title' => $this->comment->commentable->title,
            'thread_id' => $this->comment->commentable->id,
        ];
    }
}
