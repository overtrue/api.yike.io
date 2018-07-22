<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;

class ThreadFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function tab($tab)
    {
        $this->published()->whereNull('frozen_at')->whereNull('banned_at');

        switch ($tab) {
            case 'default':
                $this->latest('pinned_at')->latest('excellent_at');
                break;
            case 'featured':
                $this->whereNotNull('excellent_at')->latest('excellent_at');
                break;
            case 'recent':
                $this->latest()->latest('updated_at');
                break;
            case 'zeroComment':
                $this->doesntHave('comments')->latest();
                break;
        }
    }

    public function user($userId)
    {
        $this->where('user_id', $userId);
    }
}
