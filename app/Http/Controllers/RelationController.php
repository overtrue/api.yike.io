<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelationController extends Controller
{
    public function toggleRelation(Request $request, $relation)
    {
        $this->validate($request, [
            'relation' => 'in:like,follow,subscribe,favorite,upvote,downvote',
            'followable_id' => 'required|poly_exists:followable_type',
        ]);

        $method = 'toggle'.\studly_case($relation);

        \call_user_func_array([$request->user(), $method], $request->only(['followable_id', 'followable_type']));

        return $this->withNoContent();
    }
}
