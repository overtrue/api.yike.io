<?php

namespace App\Http\Resources;

class ThreadResource extends Resource
{
    public static function collection($resource)
    {
        $resource->loadMissing('likers', 'subscribers');

        return parent::collection($resource);
    }
}
