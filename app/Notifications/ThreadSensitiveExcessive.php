<?php

namespace App\Notifications;

use App\Mail\SensitiveExcessive;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ThreadSensitiveExcessive extends Notification implements ShouldQueue
{
    use Queueable;

    protected $admin;

    /**
     * Create a new notification instance.
     *
     * @param \App\User
     */
    public function __construct(User $admin)
    {
        $this->admin = $admin;
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
        return ['mail'];
    }

    /**
     * @param $notifiable
     *
     * @return \App\Mail\SensitiveExcessive
     */
    public function toMail($notifiable)
    {
        return (new SensitiveExcessive($notifiable))->to($this->admin['email']);
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
        ];
    }
}
