<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property int    $id
 * @property int    $contentable_id
 * @property string $contentable_type
 * @property string $body
 * @property string $markdown
 * @property \Illuminate\Database\Eloquent\Model $contentable
 * @property \Illuminate\Database\Eloquent\Relations\BelongsToMany $mentions
 */
class Content extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'contentable_type', 'contentable_id', 'body', 'markdown',
    ];

    public function contentable()
    {
        return $this->morphTo();
    }

    public function mentions()
    {
        return $this->belongsToMany(User::class, 'content_mention');
    }
}
