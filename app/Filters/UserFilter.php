<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function limit($count)
    {
        $this->take($count);
    }

    public function query($query)
    {
        $this->where('username', 'like', \sprintf('%%%s%%', $query));
    }

    public function latest()
    {
        $this->orderByDesc('created_at');
    }
}
