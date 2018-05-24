<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Resource.
 */
class Resource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        $resource->loadMissing(self::getRequestIncludes());
    }

    public static function collection($resource)
    {
        $resource->loadMissing(self::getRequestIncludes());

        return parent::collection($resource);
    }

    public static function getRequestIncludes()
    {
        $request = request();

        if ($request->has('include')) {
            return \array_map('trim', \explode(',', trim($request->get('include'), ',')));
        }

        return [];
    }
}
