<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    public function index(User $user)
    {
        return new UserResource($user);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function follow(User $user)
    {
        auth()->user()->follow($user);

        return response()->json([]);
    }

    public function unfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return response()->json([]);
    }

    public function followers(User $user)
    {
        $users = $user->followers()->get();

        return UserResource::collection($users);
    }

    public function followings(User $user)
    {
        $users = $user->followings()->get();

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param    \App\User   $user
     *
     * @return  \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    \App\User   $user
     *
     * @return  \App\Http\Resources\UserResource
     *
     * @throws  \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', auth()->user(), $user);

        $this->validate($request, [
            // validation rules...
        ]);

        $extends = $request->get('extends');

        $user->update(array_merge($request->all(), [
            'extends' => [
                'company' => $extends['company'],
                'website' => $extends['website'],
                'location' => $extends['location'],
            ]
        ]));

        return new UserResource($user);
    }
}
