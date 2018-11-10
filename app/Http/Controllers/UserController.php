<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Notification;
use App\Notifications\Welcome;
use App\User;
use Illuminate\Http\Request;
use UrlSigner;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(
            ['index', 'show', 'activities', 'activate', 'followers', 'followings', 'updateEmail', 'exists']
        );
    }

    public function index(Request $request)
    {
        $users = User::withoutBanned()->filter($request->all())->paginate($request->get('per_page', $request->get('limit', 20)));

        return UserResource::collection($users);
    }

    public function exists(Request $request)
    {
        if ($request->has('email')) {
            return ['success' => !User::whereEmail($request->get('email'))->exists()];
        }

        if ($request->has('username')) {
            return ['success' => !User::isUsernameExists($request->get('username'))];
        }

        \abort(400);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function notifications(Request $request)
    {
        $notifications = Notification::whereNotifiableId(auth()->id())
            ->latest()
            ->filter($request->all())
            ->paginate($request->get('per_page', 20));

        $request->user()->unreadNotifications->markAsRead();

        return NotificationResource::collection($notifications);
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

    public function activities(Request $request, User $user)
    {
        $activities = $user->activities()->whereIn('log_name', [
            'published.thread', 'commented.thread', 'follow.user', 'like.thread', 'subscribe.thread', 'subscribe.node',
        ])->paginate($request->get('per_page', 20));

        return ActivityResource::collection($activities);
    }

    public function sendActiveMail(Request $request)
    {
        $request->user()->sendActiveMail();

        return response()->json([
            'message' => '激活邮件已发送，请注意查收！',
        ]);
    }

    public function activate(Request $request)
    {
        if (UrlSigner::validate($request->fullUrl())) {
            $user = User::whereEmail($request->email)->first();
            $user->activate();
            $user->notify(new Welcome());

            return redirect(config('app.site_url').'?active-success=yes&type=register');
        }

        return redirect(config('app.site_url').'?active-success=no&type=register');
    }

    public function editEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $request->user()->sendUpdateMail($request->get('email'));

        return response()->json([
            'message' => '确认邮件已发送到新邮箱，请注意查收！',
        ]);
    }

    public function updateEmail(Request $request)
    {
        if (UrlSigner::validate($request->fullUrl())) {
            $user = User::findOrFail($request->get('user_id'));

            $user->update(['email' => $request->get('email')]);

            return redirect(config('app.site_url').'?active-success=yes&type=email');
        }

        return redirect(config('app.site_url').'?active-success=no&type=email');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     *
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User                $user
     *
     * @return \App\Http\Resources\UserResource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', auth()->user(), $user);

        $this->validate($request, [
            // validation rules...
        ]);

        $user->update($request->only([
            'name', 'avatar', 'realname', 'bio', 'extends', 'settings', 'cache', 'gender', 'banned_at',
        ]));

        return new UserResource($user);
    }
}
