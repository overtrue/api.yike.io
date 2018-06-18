<?php

namespace App;

use App\Traits\WithDiffForHumanTimes;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use WithDiffForHumanTimes;

    protected $appends = [
        'created_at_timeago', 'updated_at_timeago',
    ];
}
