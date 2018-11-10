<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use Filterable;

    protected $casts = [
        'data' => 'json',
    ];
}
