<?php

namespace App\Observers;

use App\User;

/**
 * Class UserObserver.
 *
 * @author overtrue <i@overtrue.me>
 */
class UserObserver
{
    public function created(User $user)
    {
    }

    public function deleted(User $user)
    {
    }
}
