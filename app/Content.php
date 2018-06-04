<?php

namespace App;

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
 * @property \Illuminate\Database\Eloquent\Model $contentable
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

        static::saving(function($content){
            if ($content->isDirty('markdown') && !empty($content->markdown)) {
                $content->body = app(\ParsedownExtra::class)->text(\emoji($content->markdown));
            }

            $content->body = \str_replace(
                ['<pre>', '<code>'],
                ['<pre class="language-php">', '<code class="language-php">'],
                $content->body
            );
            $content->body = Purifier::clean($content->body);
        });
    }

    public function contentable()
    {
        return $this->morphTo();
    }

    public function mentions()
    {
        return $this->belongsToMany(User::class, 'content_mention');
    }
}
