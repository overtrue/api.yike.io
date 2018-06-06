<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Node.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property int $node_id
 * @property string  $title
 * @property string  $icon
 * @property string  $banner
 * @property string  $description
 * @property object  $settings
 * @property object  $cache
 * @property \App\Node $node
 * @property \Illuminate\Database\Eloquent\Relations\BelongsToMany $threads
 */
class Node extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'node_id', 'title', 'icon', 'banner', 'description', 'settings', 'cache',
    ];

    protected $casts = [
        'id' => 'int',
        'node_id' => 'int',
        'settings' => 'json',
        'cache' => 'json',
    ];

    public function children()
    {
        return $this->hasMany(self::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function scopeRoot($query)
    {
        return $query->whereNodeId(0);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
