<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Profile.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property \App\User $user
 * @property int $user_id
 * @property string $from
 * @property string $uid
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $location
 * @property string $description
 * @property string $avatar
 * @property string $access_token
 * @property \Carbon\Carbon $access_token_expired_at
 * @property string $access_token_secret
 */
class Profile extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'user_id', 'from', 'uid', 'username', 'name', 'email',
        'location', 'description', 'avatar',
        'access_token', 'access_token_expired_at', 'access_token_secret',
    ];

    protected $dates = [
        'access_token_expired_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
