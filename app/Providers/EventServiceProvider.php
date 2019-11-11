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
        // 当学生的报名表获得批准时
        'App\Events\User\Student\ApproveRegistrationEvent' => [
            'App\Listeners\User\Teacher\NotifyEnrolmentManager', // 通知负责录取的老师
            'App\Listeners\User\Student\NotifyStudent',
        ],
        // 当学生的报名表获被拒绝时
        'App\Events\User\Student\RefuseRegistrationEvent' => [
            'App\Listeners\User\Student\NotifyStudent',
        ],
        // 当学生的报名表被录取时
        'App\Events\User\Student\EnrolRegistrationEvent' => [
            'App\Listeners\User\Student\NotifyStudent',
        ],
        // 当被批准的报名表被回绝时
        'App\Events\User\Student\RejectRegistrationEvent' => [
            'App\Listeners\User\Student\NotifyStudent',
            'App\Listeners\User\Teacher\NotifyRecruitmentManager', // 通知负责招生的老师
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
