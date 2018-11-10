<?php

namespace App\Notifications;

use App\Content;
use App\Mail\Mention;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MentionedMe extends Notification implements ShouldQueue
{
    use Queueable;

    public $causer;
    public $me;
    public $content;

    /**
     * Create a new notification instance.
     *
     * @param \App\User    $causer
     * @param \App\User    $me
     * @param \App\Content $content
     */
    public function __construct(User $causer, User $me, Content $content)
    {
        $this->causer = $causer;
        $this->me = $me;
        $this->content = $content;
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
     * @return \App\Mail\Mention
     */
    public function toMail($notifiable)
    {
        return (new Mention($this->causer, $notifiable, $this->content))->to($notifiable->email);
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
            'user_id' => $this->causer->id,
            'name' => $this->causer->name,
            'username' => $this->causer->username,
            'avatar' => $this->causer->avatar,
            'comment_id' => $this->content->contentable_id,
            'commentable_id' => $this->content->contentable->commentable_id,
            'commentable_type' => $this->content->contentable->commentable_type,
            'commentable_title' => $this->content->contentable->commentable->title,
            'content' => $this->content->activity_log_content,
        ];
    }
}
