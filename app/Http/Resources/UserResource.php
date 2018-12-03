<?php

namespace App\Http\Resources;

use App\User;

class UserResource extends Resource
{
    public static function collection($resource)
    {
        $resource->loadMissing('followers');

        return parent::collection($resource);
    }

    public function toArray($request)
    {
        if (\auth()->id() !== $this->resource->id) {
            $this->resource->makeHidden(User::SENSITIVE_FIELDS);
        }

        return parent::toArray($request);
    }
}
