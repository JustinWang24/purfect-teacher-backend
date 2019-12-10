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

        // 和工作流相关的事件
        'App\Events\Pipeline\Flow\FlowStarted' => [ // 流程开始成功
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
        ],
        'App\Events\Pipeline\Flow\FlowProcessed' => [ // 流程的某个步骤被同意了
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
            'App\Listeners\Pipeline\Flow\NotifyProcessor',  // 通知流程中本步骤操作的人
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
        ],
        'App\Events\Pipeline\Flow\FlowRejected' => [ // 流程的某个步骤被驳回了
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
            'App\Listeners\Pipeline\Flow\NotifyProcessor',  // 通知流程中本步骤操作的人
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
        ],
        'App\Events\Pipeline\Flow\FlowResumed' => [  // 流程的某个步骤被再一次提交
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
            'App\Listeners\Pipeline\Flow\NotifyProcessor',  // 通知流程中本步骤操作的人
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
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
