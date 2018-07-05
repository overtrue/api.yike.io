<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;

class NodeFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function hot($count)
    {
        \request()->merge(['per_page' => $count]);

        $this->orderBy('cache->threads_count', 'desc')
            ->orderBy('cache->subscribers_count', 'desc')
            ->take($count);
    }
}
