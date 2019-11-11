<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\User\Student\ApplyRecruitmentPlanEvent' => [
            'App\Listeners\User\Teacher\NotifyRecruitmentManager',
        ],
        // 当学生的报名表获得批准或者拒绝时
        'App\Events\User\Student\ApproveRegistrationEvent' => [
            'App\Listeners\User\Teacher\NotifyEnrolmentManager',
            'App\Listeners\User\Student\NotifyStudent',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
