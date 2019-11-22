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
        InternalMessage::dispatchNow(
            $event->getForm()->school_id,
            $event->getForm()->teacher_id,
            $event->getForm()->user_id,
            $event->getMessageType(),
            $event->getPriority(),
            $event->getSystemContent(),
            $event->getNextMove()
        );
    }
}
