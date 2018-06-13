<?php

namespace App\Listeners;

use Overtrue\LaravelFollow\Events\RelationAttached;

class RelationAttachedListener
{
    /**
     * Handle the event.
     *
     * @param  RelationAttached  $event
     * @return void
     */
    public function handle(RelationAttached $event)
    {
        $targetType = \strtolower(\class_basename($event->class));

        $event->getTargetsCollection()->map(function($target) use ($event, $targetType) {
            \activity($event->getRelationType().'.'.$targetType)
                ->performedOn($target)
                ->log('创建关系');
        });
    }
}
