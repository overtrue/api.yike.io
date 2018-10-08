<?php

namespace App\Jobs;

use App\Model\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ThreadSetPopular implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $threadId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($threadId)
    {
        $this->threadId = $threadId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Thread::Where(['id' => $this->threadId])->update([
            "popular_at" => \Carbon\Carbon::now()
        ]);
    }
}
