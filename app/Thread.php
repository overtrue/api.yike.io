<?php

namespace App;

use App\Contracts\Commentable;
use App\Traits\EsHighlightAttributes;
use App\Traits\OnlyActivatedUserCanCreate;
use App\Traits\WithDiffForHumanTimes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Overtrue\LaravelFollow\Traits\CanBeSubscribed;

/**
 * Class Thread.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property int            $user_id
 * @property string         $title
 * @property \Carbon\Carbon $excellent_at
 * @property \Carbon\Carbon $pinned_at
 * @property \Carbon\Carbon $frozen_at
 * @property \Carbon\Carbon $banned_at
 * @property bool           $has_pinned
 * @property bool           $has_banned
 * @property bool           $has_excellent
 * @property bool           $has_frozen
 * @property object         $cache
 *
 * @method static \App\Thread published()
 */
class Thread extends Model implements Commentable
{
    use SoftDeletes, Filterable, OnlyActivatedUserCanCreate, WithDiffForHumanTimes,
        CanBeSubscribed, CanBeFavorited, CanBeLiked, Searchable, EsHighlightAttributes;

    protected $fillable = [
        'user_id', 'title', 'excellent_at', 'node_id',
        'pinned_at', 'frozen_at', 'banned_at', 'published_at', 'cache',
        'cache->views_count', 'cache->comments_count', 'cache->likes_count', 'cache->subscriptions_count',
        'cache->last_reply_user_id', 'cache->last_reply_user_name',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'is_excellent' => 'bool',
        'cache' => 'array',
    ];

    const CACHE_FIELDS = [
        'views_count' => 0,
        'comments_count' => 0,
        'likes_count' => 0,
        'subscriptions_count' => 0,
        'last_reply_user_id' => 0,
        'last_reply_user_name' => null,
    ];

    const SENSITIVE_FIELDS = [
        'excellent_at', 'pinned_at', 'frozen_at', 'banned_at',
    ];

    protected $dates = [
        'excellent_at', 'pinned_at', 'frozen_at', 'banned_at', 'published_at',
    ];

    protected $with = ['user'];

    protected $appends = [
        'has_pinned', 'has_banned', 'has_excellent', 'has_frozen',
        'created_at_timeago', 'updated_at_timeago', 'has_liked',
        'has_subscribed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Thread $thread) {
            $thread->user_id = \auth()->id();

            static::throttleCheck($thread->user);
        });

        static::saving(function (Thread $thread) {
            if (\array_has($thread->getDirty(), self::SENSITIVE_FIELDS) && !\request()->user()->is_admin) {
                abort('非法请求！');
            }

            foreach ($thread->getDirty() as $field => $value) {
                if (\ends_with($field, '_at')) {
                    $thread->$field = $value ? now() : null;
                }
            }
        });

        $saveContent = function (Thread $thread) {
            if (request()->routeIs('threads.*') && \request()->has('content')) {
                $data = array_only(\request()->input('content', []), \request()->input('type', 'markdown'));
                $thread->content()->updateOrCreate(['contentable_id' => $thread->id], $data);
                $thread->loadMissing('content');
            }
        };

        static::updated($saveContent);
        static::created($saveContent);

        static::saving(function ($thread) {
            if (\request('is_draft', false)) {
                $thread->published_at = null;
            } elseif (!$thread->published_at) {
                $thread->published_at = now();
            }
        });
    }

    public function toSearchableArray()
    {
        $content = $this->content ? $this->content->markdown : '';

        return array_merge(\array_except($this->toArray(), 'user'), \compact('content'));
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function content()
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', now())
            ->whereHas('user', function ($q) {
                $q->whereNotNull('activated_at')->whereNull('banned_at');
            });
    }

    public function getCacheAttribute()
    {
        return array_merge(self::CACHE_FIELDS, json_decode($this->attributes['cache'] ?? '{}', true));
    }

    public function getHasPinnedAttribute()
    {
        return (bool) $this->pinned_at;
    }

    public function getHasBannedAttribute()
    {
        return (bool) $this->banned_at;
    }

    public function getHasExcellentAttribute()
    {
        return (bool) $this->excellent_at;
    }

    public function getHasFrozenAttribute()
    {
        return (bool) $this->frozen_at;
    }

    public function getHasSubscribedAttribute()
    {
        if (auth()->guest()) {
            return false;
        }

        return $this->relationLoaded('subscribers') ? $this->subscribers->contains(auth()->user()) : $this->isSubscribedBy(auth()->user());
    }

    public function getHasLikedAttribute()
    {
        if (auth()->guest()) {
            return false;
        }

        return $this->relationLoaded('likers') ? $this->likers->contains(auth()->user()) : $this->isLikedBy(auth()->user());
    }

    public function getUrlAttribute()
    {
        return \sprintf('%s/threads/%d', \config('app.site_url'), $this->id);
    }

    /**
     * @param \App\Comment $lastComment
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function afterCommentCreated(Comment $lastComment)
    {
        $lastComment->user->subscribe($this);
    }

    public function refreshCache()
    {
        $lastComment = $this->comments()->latest()->first();

        $this->update(['cache' => \array_merge(self::CACHE_FIELDS, [
            'views_count' => $this->cache['views_count'],
            'comments_count' => $this->comments()->count(),
            'likes_count' => $this->likers()->count(),
            'favoriters_count' => $this->favoriters()->count(),
            'subscriptions_count' => $this->subscribers()->count(),
            'last_reply_user_id' => $lastComment ? $lastComment->user->id : 0,
            'last_reply_user_name' => $lastComment ? $lastComment->user->name : '',
        ])]);
    }

    public static function throttleCheck(User $user)
    {
        $lastThread = $user->threads()->latest()->first();

        if ($lastThread && $lastThread->created_at->gt(now()->subHours(config('throttle.thread.create')))) {
            \abort(403, '发贴太频繁');
        }
    }
}
