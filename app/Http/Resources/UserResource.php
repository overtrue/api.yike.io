<?php

namespace App\Http\Resources;

class UserResource extends Resource
{
    public static function collection($resource)
    {
        $resource->loadMissing('followers');

        return parent::collection($resource);
    }
}
