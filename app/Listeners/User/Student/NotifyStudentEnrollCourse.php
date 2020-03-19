<?php


namespace App\Listeners\User\Student;


use App\Events\HasEnrollCourseForm;
use App\Jobs\Notifier\InternalMessage;
use Illuminate\Support\Facades\Log;

class NotifyStudentEnrollCourse
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
     * @param  HasEnrollCourseForm  $event
     * @return void
     */
    public function handle(HasEnrollCourseForm $event)
    {
        //@TODO 应该是选课的消息通知好像没注册事件？ 需补充消息新增的字段
        /*InternalMessage::dispatchNow(
            $event->getForm()->school_id,
            $event->getForm()->teacher_id,
            $event->getForm()->user_id,
            $event->getMessageType(),
            $event->getPriority(),
            $event->getSystemContent(),
            $event->getNextMove()
        );*/
    }
}
