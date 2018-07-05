<?php

namespace App\Providers;

use App\Comment;
use App\Observers\CommentObserver;
use App\Observers\RelationObserver;
use App\Observers\ThreadObserver;
use App\Observers\UserObserver;
use App\Thread;
use App\User;
use App\Validators\HashValidator;
use App\Validators\IdNumberValidator;
use App\Validators\KeepWordValidator;
use App\Validators\PhoneValidator;
use App\Validators\PhoneVerifyCodeValidator;
use App\Validators\PolyExistsValidator;
use App\Validators\TicketValidator;
use App\Validators\UserUniqueContentValidator;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Overtrue\EasySms\EasySms;
use Overtrue\LaravelFollow\FollowRelation;

class AppServiceProvider extends ServiceProvider
{
    protected $validators = [
        'poly_exists' => PolyExistsValidator::class,
        'phone' => PhoneValidator::class,
        'id_no' => IdNumberValidator::class,
        'verify_code' => PhoneVerifyCodeValidator::class,
        'keep_word' => KeepWordValidator::class,
        'hash' => HashValidator::class,
        'ticket' => TicketValidator::class,
        'user_unique_content' => UserUniqueContentValidator::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Resource::withoutWrapping();

        Carbon::setLocale('zh');

        User::observe(UserObserver::class);
        Thread::observe(ThreadObserver::class);
        Comment::observe(CommentObserver::class);

        $this->registerValidators();

        Horizon::auth(function ($request) {
            return true;
        });
    }

    /**
     * Register validators.
     */
    protected function registerValidators()
    {
        foreach ($this->validators as $rule => $validator) {
            Validator::extend($rule, "{$validator}@validate");
        }
    }

    protected function registerSmsService()
    {
        $this->app->singleton(EasySms::class, function () {
            return new EasySms(config('sms'));
        });

        $this->app->alias(EasySms::class, 'sms');
    }
}
