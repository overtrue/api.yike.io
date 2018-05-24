<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanLike;
use Overtrue\LaravelFollow\Traits\CanSubscribe;

/**
 * Class User.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $avatar
 * @property string $realname
 * @property string $bio
 * @property object $extends
 * @property object $settings
 * @property int $level
 * @property bool $is_admin
 * @property bool $has_banned
 * @property bool $has_activated
 * @property object $cache
 * @property string $github_id
 * @property string $linkedin_id
 * @property string $twitter_id
 * @property string $weibo_url
 * @property \Carbon\Carbon $last_active_at
 * @property \Carbon\Carbon $banned_at
 * @property \Carbon\Carbon $activated_at
 * @property \Illuminate\Database\Eloquent\Relations\HasMany $profiles
 * @property \Illuminate\Database\Eloquent\Relations\HasMany $threads
 * @property \Illuminate\Database\Eloquent\Relations\HasMany $comments
 */
class User extends Authenticatable
{
    use Notifiable, CanFavorite, CanLike, CanFollow, CanSubscribe;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'avatar', 'realname',
        'bio', 'extends', 'settings', 'level', 'is_admin', 'cache',
        'github_id', 'linkedin_id', 'twitter_id', 'weibo_url',
        'last_active_at', 'banned_at', 'activated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'last_active_at', 'banned_at', 'activated_at',
    ];

    protected $casts = [
        'is_admin' => 'bool',
        'extends' => 'json',
        'cache' => 'json',
        'settings' => 'json',
    ];

    protected $appends = [
        'has_banned', 'has_activated',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getHasBannedAttribute()
    {
        return (bool) $this->banned_at;
    }

    public function getHasActivatedAttribute()
    {
        return (bool) $this->activated_at;
    }
}
