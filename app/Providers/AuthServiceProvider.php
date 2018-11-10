<?php

namespace App\Providers;

use App\Banner;
use App\Comment;
use App\Node;
use App\Policies\BannerPolicy;
use App\Policies\CommentPolicy;
use App\Policies\NodePolicy;
use App\Policies\ProfilePolicy;
use App\Policies\TagPolicy;
use App\Policies\ThreadPolicy;
use App\Policies\UserPolicy;
use App\Profile;
use App\Tag;
use App\Thread;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Banner::class => BannerPolicy::class,
        Comment::class => CommentPolicy::class,
        Node::class => NodePolicy::class,
        Profile::class => ProfilePolicy::class,
        Tag::class => TagPolicy::class,
        Thread::class => ThreadPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
