<?php

namespace App\Jobs;

use App\Notifications\ThreadSensitiveExcessive;
use App\Services\Filter\SensitiveFilter;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

/**
 * Class FilterThreadSensitiveWords.
 *
 * @author v_haodouliu <haodouliu@gmail.com>
 */
class FilterThreadSensitiveWords
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
        $sensitiveFilter = \app(SensitiveFilter::class);

        $isLegal = $sensitiveFilter->isLegal($this->content);

        if ($isLegal) {
            $cacheKey = 'thread_sensitive_trigger_'.Auth::id();

            if (!Cache::has($cacheKey)) {
                Cache::forever($cacheKey, 0);
            }

            if (Cache::get($cacheKey) >= Thread::THREAD_SENSITIVE_TRIGGER_LIMIT) {
                //发送邮件
                Notification::send(User::admin()->get(), new ThreadSensitiveExcessive(User::first()));
            }

            Cache::increment($cacheKey);

            $this->content = $sensitiveFilter->replace($this->content, '***');
        }

        return $this->content;
    }
}
