<?php


namespace App\Observers;
use Overtrue\LaravelFollow\FollowRelation;


/**
 * Class RelationObserver
 *
 * @author overtrue <i@overtrue.me>
 */
class RelationObserver
{
    public function created(FollowRelation $relation)
    {
        $targetType = \strtolower($relation->followable_type);

        \activity($relation->relation.$targetType)->performedOn($relation->followable)->log('创建关系');
    }

    public function saved(FollowRelation $relation)
    {
    }

    public function deleted(FollowRelation $relation)
    {

    }
}