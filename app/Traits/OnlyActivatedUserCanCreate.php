<?php

namespace App\Traits;

/**
 * Trait OnlyActivatedUserCanCreate.
 *
 * @author overtrue <i@overtrue.me>
 */
trait OnlyActivatedUserCanCreate
{
    public static function bootOnlyActivatedUserCanCreate()
    {
        static::creating(function () {
            if (auth()->guest() || !auth()->user()->has_activated) {
                \abort(403, 'Only activated user can create.');
            }
        });
    }
}
