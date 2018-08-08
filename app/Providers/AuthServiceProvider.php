<?php

namespace App\Providers;

use App\Banner;
use App\Comment;
use App\Node;
use App\Polices\BannerPolicy;
use App\Polices\CommentPolicy;
use App\Polices\ContentPolicy;
use App\Polices\NodePolicy;
use App\Polices\ProfilePolicy;
use App\Polices\TagPolicy;
use App\Polices\ThreadPolicy;
use App\Polices\UserPolicy;
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
    protected $Polices = [
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
        $this->registerPolices();

        Passport::routes();
    }
}
