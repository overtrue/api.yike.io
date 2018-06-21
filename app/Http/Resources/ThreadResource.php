<?php

namespace App\Http\Resources;

class ThreadResource extends Resource
{
    public static function collection($resource)
    {
        $resource->load('likers', 'subscribers');

        return parent::collection($resource);
    }
}
