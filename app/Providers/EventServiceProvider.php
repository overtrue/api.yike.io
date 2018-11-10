<?php

namespace App\Providers;

use App\Listeners\RelationToggledListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Overtrue\LaravelFollow\Events\RelationToggled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RelationToggled::class => [
            RelationToggledListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
