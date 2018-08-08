<?php

namespace App\Policies;

use App\Node;
use App\User;

class NodePolicy extends Policy
{
    /**
     * Determine whether the user can view the node.
     *
     * @param \App\User $authUser
     * @param \App\Node $node
     *
     * @return bool
     */
    public function view(User $authUser, Node  $node)
    {
        return true;
    }

    /**
     * Determine whether the user can create nodes.
     *
     * @param \App\User $authUser
     *
     * @return bool
     */
    public function create(User $authUser)
    {
        return $authUser->can('create-node');
    }

    /**
     * Determine whether the user can update the node.
     *
     * @param \App\User $authUser
     * @param \App\Node $node
     *
     * @return bool
     */
    public function update(User $authUser, Node  $node)
    {
        return $node->user_id == $authUser->id || $authUser->can('update-node');
    }

    /**
     * Determine whether the user can delete the node.
     *
     * @param \App\User $authUser
     * @param \App\Node $node
     *
     * @return bool
     */
    public function delete(User $authUser, Node  $node)
    {
        return $node->user_id == $authUser->id || $authUser->can('delete-node');
    }
}
