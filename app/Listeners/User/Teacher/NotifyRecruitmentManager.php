<?php
/**
 * 当学生填写登记表成功的事件发生时的监听器
 */
namespace App\Listeners\User\Teacher;

use App\Events\HasRegistrationForm;
use App\Events\User\Student\ApplyRecruitmentPlanEvent;
use App\Jobs\Notifier\InternalMessage;
use App\Models\Misc\SystemNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyRecruitmentManager
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HasRegistrationForm  $event
     * @return void
     */
    public function handle(HasRegistrationForm $event)
    {
        // 事件发生后, 现在采用系统内部消息通知的方式通知老师. 要不几千张报名表, 累死了
        InternalMessage::dispatchNow(
            $event->getForm()->school_id,
            $event->getForm()->lastOperator->id,
            $event->getForm()->plan->manager_id,
            $event->getMessageType(),
            $event->getPriority(),
            $event->getSystemContent(),
            $event->getNextMove()
        );
    }
}
