<?php

namespace App;

use App\Traits\OnlyActivatedUserCanCreate;
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
 */
class Thread extends Model
{
    use SoftDeletes, Filterable, CanBeSubscribed, CanBeFavorited, CanBeLiked, OnlyActivatedUserCanCreate;

    protected $fillable = [
        'user_id', 'title', 'excellent_at',
        'pinned_at', 'frozen_at', 'banned_at', 'cache',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'is_excellent' => 'bool',
        'cache' => 'json',
    ];

    protected $dates = [
        'excellent_at', 'pinned_at', 'frozen_at', 'banned_at',
    ];

    protected $appends = [
        'has_pinned', 'has_banned', 'has_excellent', 'has_frozen',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($thread){
            $thread->user_id = \auth()->id();
        });

        static::saved(function($thread){
            $thread->content()->updateOrCreate(['body' => \request('body')]);
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
