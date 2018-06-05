<?php

namespace App;

use App\Traits\OnlyActivatedUserCanCreate;
use App\Traits\WithDiffForHumanTimes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Overtrue\LaravelFollow\Traits\CanBeSubscribed;

/**
 * Class Thread.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property int $user_id
 * @property string $title
 * @property \Carbon\Carbon $excellent_at
 * @property \Carbon\Carbon $pinned_at
 * @property \Carbon\Carbon $frozen_at
 * @property \Carbon\Carbon $banned_at
 * @property bool $has_pinned
 * @property bool $has_banned
 * @property bool $has_excellent
 * @property bool $has_frozen
 * @property object $cache
 * @method   static \App\Thread published()
 */
class Thread extends Model
{
    use SoftDeletes, Filterable, CanBeSubscribed, CanBeFavorited, CanBeLiked, OnlyActivatedUserCanCreate, WithDiffForHumanTimes;

    protected $fillable = [
        'user_id', 'title', 'excellent_at', 'node_id',
        'pinned_at', 'frozen_at', 'banned_at', 'published_at', 'cache',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'is_excellent' => 'bool',
        'cache' => 'json',
    ];

    protected $dates = [
        'excellent_at', 'pinned_at', 'frozen_at', 'banned_at', 'published_at',
    ];

    protected $with = ['user'];

    protected $appends = [
        'has_pinned', 'has_banned', 'has_excellent', 'has_frozen', 'created_at_timeago', 'updated_at_timeago',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($thread){
            $thread->user_id = \auth()->id();
        });

        static::saving(function($thread){
            $thread->published_at = \request('is_draft', false) ? null : now();
        });

        static::saved(function($thread){
            $data = array_only(\request('content'), \request('type', 'markdown'));
            $thread->content()->updateOrCreate(['contentable_id' => $thread->id], $data);
            $thread->loadMissing('content');
        });
    }

    public function comments()
    {
        return $this->morphToMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', now());
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
}
