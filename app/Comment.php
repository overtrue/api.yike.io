<?php

namespace App;

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
    use SoftDeletes, Filterable, CanBeVoted;

    protected $fillable = [
        'commentable_id', 'commentable_type', 'user_id', 'banned_at', 'cache',
    ];

    protected $dates = [
        'banned_at',
    ];

    protected $casts = [
        'cache' => 'object',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($thread){
            $thread->user_id = \auth()->id();
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
        return $this->hasOne(Content::class);
    }
}
