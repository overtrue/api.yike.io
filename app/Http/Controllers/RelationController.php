<?php

namespace App\Http\Controllers;

use App\Node;
use App\Notifications\LikedMyThread;
use App\Notifications\NewFollower;
use App\Notifications\SubscribedMyThread;
use App\Thread;
use App\User;

class RelationController extends Controller
{
    public function toggleFollow(User $user, string $type)
    {
        if ($type == 'follow') {
            auth()->user()->follow($user);

            $user->notify(new NewFollower(auth()->user()));
        } else {
            auth()->user()->unfollow($user);
        }

        return response()->json([]);
    }

    public function toggleActionThread(Thread $thread, string $type)
    {
        if (in_array($type, ['like', 'unlike'])) {
            return $this->toggleLike($type, $thread);
        } else if (in_array($type, ['subscribe', 'unsubscribe'])) {
            return $this->toggleSubscribeThread($type, $thread);
        }
    }

    public function toggleLike(Thread $thread, string $type)
    {
        if ($type == 'like') {
            auth()->user()->like($thread);

            $thread->user->notify(new LikedMyThread($thread, auth()->user()));
        } else {
            auth()->user()->unlike($thread);
        }

        return response()->json([]);
    }

    public function toggleSubscribeThread(Thread $thread, string $type)
    {
        if ($type == 'subscribe') {
            auth()->user()->subscribe($thread);

            $thread->user->notify(new SubscribedMyThread($thread, auth()->user()));
        } else {
            auth()->user()->unsubscribe($thread);
        }

        return response()->json([]);
    }

    public function toggleSubscribeNode(Node $node, string $type)
    {
        if ($type == 'subscribe') {
            auth()->user()->subscribe($node);
        } else {
            auth()->user()->unsubscribe($node);
        }

        $node->refreshCache();

        return response()->json([]);
    }
}
