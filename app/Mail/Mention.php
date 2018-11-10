<?php

namespace App\Mail;

use App\Content;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mention extends Mailable
{
    use Queueable, SerializesModels;

    public $causer;
    public $user;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param \App\User    $causer
     * @param \App\User    $user
     * @param \App\Content $content
     */
    public function __construct(User $causer, User $user, Content $content)
    {
        $this->causer = $causer;
        $this->user = $user;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('有人在讨论中提到了您')->markdown('mails.mention');
    }
}
