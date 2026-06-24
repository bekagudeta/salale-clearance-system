<?php

namespace App\Providers;

use App\Events\ClearanceApproved;
use App\Events\ClearanceCompleted;
use App\Events\ClearanceForwarded;
use App\Events\ClearanceRejected;
use App\Events\ClearanceSubmitted;
use App\Listeners\LogClearanceActivity;
use App\Listeners\NotifyDepartments;
use App\Listeners\NotifyServiceDepartments;
use App\Listeners\NotifyStudent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ClearanceSubmitted::class => [
            NotifyDepartments::class,
        ],
        ClearanceForwarded::class => [
            NotifyServiceDepartments::class,
        ],
    ];

    /**
     * Subscriber classes.
     * These listeners use the subscriber pattern (subscribe method).
     */
    protected $subscribe = [
        LogClearanceActivity::class,
        NotifyStudent::class,
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
