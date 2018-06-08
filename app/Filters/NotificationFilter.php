<?php

namespace App\Filters;

use App\Notifications\CommentMyThread;
use App\Notifications\LikedMyThread;
use App\Notifications\NewFollower;
use App\Notifications\SubscribedMyThread;
use EloquentFilter\ModelFilter;

class NotificationFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function tab($tab)
    {
        switch ($tab) {
            case 'follow':
                $this->whereType(NewFollower::class);
                break;
            case 'subscribe':
                $this->whereType(SubscribedMyThread::class);
                break;
            case 'comment':
                $this->whereType(CommentMyThread::class);
                break;
            case 'like':
                $this->whereType(LikedMyThread::class);
                break;
            default:
                break;
        }
    }
}
