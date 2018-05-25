<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tag.
 *
 * @author overtrue <i@overtrue.me>
 *
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $color
 */
class Tag extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'name', 'slug', 'icon', 'color',
    ];

    protected $casts = [
        'id' => 'int',
    ];
}
