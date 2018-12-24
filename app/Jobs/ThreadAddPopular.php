<?php

namespace App\Jobs;

use App\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class ThreadAddPopular
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
     * @return void
     */
    public function handle()
    {
        if ($this->thread->popular_at) {
            return;
        }

        $likes_count = $this->thread->likes()->count();
        if ($likes_count >= Thread::POPULAR_CONDITION_LIKES_COUNT) {
            $this->thread->popular_at = \now();
            $this->thread->save();

            return;
        }

        $views_count = $this->thread->cache['views_count'];
        if ($views_count >= Thread::POPULAR_CONDITION_VIEWS_COUNT) {
            $this->thread->popular_at = \now();
            $this->thread->save();

            return;
        }

        $comments_count = $this->thread->comments()->count();
        if ($comments_count >= Thread::POPULAR_CONDITION_COMMENTS_COUNT) {
            $this->thread->popular_at = \now();
            $this->thread->save();

            return;
        }
    }
}
