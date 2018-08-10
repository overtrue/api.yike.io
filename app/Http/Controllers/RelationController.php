<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Overtrue\LaravelFollow\FollowRelation;

class RelationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'active_user'])->except('index');
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'followable_id' => 'required|poly_exists:followable_type',
        ]);

        return FollowRelation::query()->where('followable_id', $request->get('followable_id'))
                                        ->where('followable_type', $request->get('followable_type'))
                                        ->where('relation', $request->get('relation', 'follow'))
                                        ->paginate($request->get('per_page', 50));
    }

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
