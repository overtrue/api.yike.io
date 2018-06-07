<?php
namespace App\Http\Controllers;

use App\Notifications\NewFollower;
use App\Notifications\Welcome;
use UrlSigner;
use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'activate', 'followers', 'followings']);
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

        activity('follow')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log(auth()->user()->name . " 关注了 {$user->name}");

        $user->notify(new NewFollower(auth()->user()));

        return response()->json([]);
    }

    public function unfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return response()->json([]);
    }

    public function followers(Request $request, User $user)
    {
        $users = $user->followers()->paginate($request->get('per_page', 20));

        return UserResource::collection($users);
    }

    public function followings(Request $request, User $user)
    {
        $users = $user->followings()->paginate($request->get('per_page', 20));

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

            auth()->user()->notify(new Welcome());

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
