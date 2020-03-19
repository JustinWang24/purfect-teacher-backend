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
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
        ],
        'App\Events\Pipeline\Flow\FlowProcessed' => [ // 流程的某个步骤被同意了
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
            //'App\Listeners\Pipeline\Flow\NotifyProcessor',  // 通知流程中本步骤操作的人
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
        ],
        'App\Events\Pipeline\Flow\FlowRejected' => [ // 流程的某个步骤被驳回了
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
        ],
        'App\Events\Pipeline\Flow\FlowBusiness' => [//流程通过后通知对应的业务
            'App\Listeners\Pipeline\Flow\NotifyBusiness',
        ],
        /*'App\Events\Pipeline\Flow\FlowResumed' => [  // 流程的某个步骤被再一次提交
            'App\Listeners\Pipeline\Flow\NotifyStarter',    // 通知发起流程的人
            'App\Listeners\Pipeline\Flow\NotifyProcessor',  // 通知流程中本步骤操作的人
            'App\Listeners\Pipeline\Flow\NotifyNextProcessors',  // 通知流程中下一步需要知晓的人
        ],*/

        'App\Events\User\ForgetPasswordEvent' => [  // 忘记密码
            'App\Listeners\Send\Sms',  // 发送短信
        ],

        'App\Events\User\UpdateUserMobileEvent' => [  // 修改手机号
            'App\Listeners\Send\Sms',  // 发送短信
        ],

        'App\Events\User\SendCodeVisiterMobileEvent' => [  // 发送访客短信
            'App\Listeners\Send\SendCodeVisiterMobile',  // 发送访客短信
        ],

        //教师迟到时给教务处领导发短信通知
        'App\Events\User\TeacherBeLateEvent' =>[
            'App\Listeners\User\Teacher\NotifyTeacherBeLate'
        ],

        //发送通知时发送系统消息通知所有人
        'App\Events\SystemNotification\NoticeSendEvent' => [
            'App\Listeners\SystemNotification\NoticeSend'
        ],
        //wifi报修处理时发送系统消息通知报修人
        'App\Events\SystemNotification\WifiIssueEvent' => [
            'App\Listeners\SystemNotification\WifiIssue'
        ],

        //报名被批准或拒绝发送系统消息
        'App\Events\SystemNotification\ApproveOpenMajorEvent' => [
            'App\Listeners\SystemNotification\ApproveOpenMajor'
        ],

        //选修课开班或解散时发送系统消息
        'App\Events\SystemNotification\ApproveElectiveCourseEvent' => [
            'App\Listeners\SystemNotification\ApproveElectiveCourse'
        ],
        //内部信发送时发送系统消息
        'App\Events\SystemNotification\OaMessageEvent' => [
            'App\Listeners\SystemNotification\OaMessage'
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
