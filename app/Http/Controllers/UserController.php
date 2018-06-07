<?php
namespace App\Http\Controllers;

use UrlSigner;
use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'activate']);
    }

    public function index(Request $request)
    {
        $users = User::filter($request->all())->paginate($request->get('per_page', 20));

        return UserResource::collection($users);
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

    public function sendActiveMail(Request $request)
    {
        $request->user()->sendActiveMail();

        return response()->json([
            'message' => '激活邮件已发送，请注意查收！'
        ]);
    }

    public function activate(Request $request)
    {
        if (UrlSigner::validate($request->fullUrl())) {
            User::whereEmail($request->email)->first()->activate();

            return \redirect(config('app.site_url').'?active-success=yes');
        }

        return \redirect(config('app.site_url').'?active-success=no');
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
