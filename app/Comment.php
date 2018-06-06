<?php

namespace App;

use App\Traits\OnlyActivatedUserCanCreate;
use App\Traits\WithDiffForHumanTimes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeVoted;

/**
 * Class Comment.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property int   $commentable_id
 * @property string    $commentable_type
 * @property int   $user_id
 * @property bool   $banned_at
 * @property object    $cache
 * @property \App\User $user
 */
class Comment extends Model
{
    use SoftDeletes, Filterable, CanBeVoted, OnlyActivatedUserCanCreate, WithDiffForHumanTimes;

    const COMMENTABLES = [
        Thread::class,
    ];

    protected $fillable = [
        'commentable_id', 'commentable_type', 'user_id', 'banned_at', 'cache',
    ];

    protected $dates = [
        'banned_at',
    ];

    protected $with = [
        'user', 'content',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'cache' => 'object',
    ];

    protected $appends = [
        'created_at_timeago', 'updated_at_timeago',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($thread){
            $thread->user_id = \auth()->id();
        });

        static::saved(function($comment){
            $data = array_only(\request('content'), \request('type', 'markdown'));
            $comment->content()->updateOrCreate(['contentable_id' => $comment->id], $data);
            $comment->loadMissing('content');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function content()
    {
        return $this->morphOne(Content::class, 'contentable');
    }
}
