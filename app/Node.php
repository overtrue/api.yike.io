<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeSubscribed;

/**
 * Class Node.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property int                                                   $node_id
 * @property string                                                $title
 * @property string                                                $icon
 * @property string                                                $banner
 * @property string                                                $description
 * @property object                                                $settings
 * @property object                                                $cache
 * @property \App\Node                                             $node
 * @property \Illuminate\Database\Eloquent\Relations\BelongsToMany $threads
 */
class Node extends Model
{
    use SoftDeletes, Filterable, CanBeSubscribed;

    protected $fillable = [
        'node_id', 'title', 'icon', 'banner', 'description', 'settings', 'cache',
        'cache->threads_count', 'cache->subscribers_count',
    ];

    protected $casts = [
        'id' => 'int',
        'node_id' => 'int',
        'settings' => 'json',
        'cache' => 'json',
    ];

    protected $appends = [
        'has_subscribed',
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
        return $query->whereNull('node_id');
    }

    public function scopeLeaf($query)
    {
        return $query->whereNotNull('node_id');
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function refreshCache()
    {
        $this->update([
            'cache->threads_count' => $this->threads()->count(),
            'cache->subscribers_count' => $this->subscribers()->count(),
        ]);
    }

    public function getHasSubscribedAttribute()
    {
        return $this->isSubscribedBy(auth()->user());
    }
}
