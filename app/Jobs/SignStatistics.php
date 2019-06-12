<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SignStatistics implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * ActivityStatistics constructor.
     *
     * @param \App\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $redis = app('redis.connection');

        $monthKey = \now()->format('Ym');
        $dayKey = \now()->format('Ymd');

        if ($redis->getbit('login:'.$dayKey, $this->user->id)) {
            return;
        }

        $redis->setbit('login:'.$dayKey, $this->user->id, 1);
        $redis->zincrby('rank:'.$dayKey, 1, $this->user->id);

        $scans = $redis->scan(0, 'match', 'rank:'.$monthKey.'*');

        $ranKeys = \end($scans);

        \reset($scans);

        while (0 != \current($scans)) {
            $scans = $redis->scan(\current($scans), 'match', 'rank:'.$monthKey.'*');

            $ranKeys = \array_merge($ranKeys, \end($scans));

            \reset($scans);
        }

        // 避免重复累加
        $redis->del('monthRank:'.$monthKey);
        $redis->zunionstore('monthRank:'.$monthKey, $ranKeys);

        Cache::forever('monthRank:'.$monthKey, $redis->zrevrange('monthRank:'.$monthKey, 0, -1, 'withscores'));
    }
}
