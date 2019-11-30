<?php

namespace App\Listeners\User\Teacher;


use App\Events\HasRegistrationForm;
use App\Jobs\Notifier\InternalMessage;


class NotifyEnrolmentManager
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
        //
        InternalMessage::dispatchNow(
            $event->getForm()->school_id,
            $event->getForm()->lastOperator->id,
            $event->getForm()->plan->enrol_manager,
            $event->getMessageType(),
            $event->getPriority(),
            $event->getSystemContent(),
            $event->getNextMove()
        );
    }
}
