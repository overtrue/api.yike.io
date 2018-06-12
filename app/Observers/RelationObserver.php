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
    }

    public function deleted(FollowRelation $relation)
    {

    }
}