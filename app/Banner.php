<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Banner.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property string $name
 * @property string $description
 * @property array  $banners
 */
class Banner extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'name', 'description', 'banners',
    ];

    protected $casts = [
        'banners' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }
}
