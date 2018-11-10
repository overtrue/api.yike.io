<?php

namespace App;

use App\Jobs\FetchContentMentions;
use App\Traits\OnlyActivatedUserCanCreate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mews\Purifier\Facades\Purifier;

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
 * @property string activity_log_content
 * @property \Illuminate\Database\Eloquent\Model                   $contentable
 * @property \Illuminate\Database\Eloquent\Relations\BelongsToMany $mentions
 */
class Content extends Model
{
    use SoftDeletes, Filterable, OnlyActivatedUserCanCreate;

    protected $fillable = [
        'contentable_type', 'contentable_id', 'body', 'markdown',
    ];

    protected $casts = [
        'id' => 'int',
        'contentable_id' => 'int',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($content) {
            if ($content->isDirty('markdown') && !empty($content->markdown)) {
                $content->body = self::toHTML($content->markdown);
            }

            $content->body = Purifier::clean($content->body);
        });

        static::saved(function ($content) {
            \dispatch(new FetchContentMentions($content));
        });
    }

    public static function toHTML(string $markdown)
    {
        return app(\ParsedownExtra::class)->text(\emoji($markdown));
    }

    public function contentable()
    {
        return $this->morphTo();
    }

    public function mentions()
    {
        return $this->belongsToMany(User::class, 'content_mention');
    }

    public function getActivityLogContentAttribute()
    {
        return \str_limit(\strip_tags($this->body), 200);
    }
}
