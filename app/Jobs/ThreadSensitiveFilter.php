<?php

namespace App\Jobs;

use App\Notifications\ThreadSensitiveExcessive;
use App\Services\Filter\SensitiveFilterHelper;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class ThreadSensitiveFilter.
 *
 * @author v_haodouliu <haodouliu@gmail.com>
 */
class ThreadSensitiveFilter
{
    protected $content;

    /**
     * ThreadSensitiveFilter constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function handle()
    {
        $sensitiveFilterHelper = \app(SensitiveFilterHelper::class);

        $isLegal = $sensitiveFilterHelper->islegal($this->content);

        if ($isLegal) {
            $cacheKey = 'thread_sensitive_trigger_'.Auth::id();

            if (!Cache::has($cacheKey)) {
                Cache::forever($cacheKey, 0);
            }

            if (Cache::get($cacheKey) >= Thread::THREAD_SENSITIVE_TRIGGER_LIMIT) {
                //发送邮件
                Auth::user()->notify(new ThreadSensitiveExcessive(User::first()));
            }

            Cache::increment($cacheKey);

            $this->content = $sensitiveFilterHelper->replace($this->content, '***');
        }

        return $this->content;
    }
}
