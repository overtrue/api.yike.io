<?php

namespace App\Jobs;

use App\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class ThreadAddPopular.
 *
 * @author v_haodouliu <haodouliu@gmail.com>
 */
class ThreadAddPopular implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $thread;

    /**
     * ThreadAddPopular constructor.
     *
     * @param \App\Thread $thread
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if ($this->thread->popular_at) {
            return;
        }

        if ($this->thread->cache['views_count'] >= Thread::POPULAR_CONDITION_VIEWS_COUNT
            || $this->thread->likes()->count() >= Thread::POPULAR_CONDITION_LIKES_COUNT
            || $this->thread->comments()->count() >= Thread::POPULAR_CONDITION_COMMENTS_COUNT) {
            $this->thread->update(['popular_at' => now()]);
        }
    }
}
