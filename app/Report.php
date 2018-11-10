<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use Filterable;

    protected $fillable = [
        'user_id', 'remark',
    ];
}
