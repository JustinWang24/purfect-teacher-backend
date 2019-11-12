<?php

namespace App\Listeners\User\Student;

use App\Events\User\Student\ApproveRegistrationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CanReachByMobilePhone;
use App\Utils\Misc\SmsFactory;

class NotifyStudent
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
     * @param  CanReachByMobilePhone  $event
     * @return void
     */
    public function handle(CanReachByMobilePhone $event)
    {
        $sms = SmsFactory::GetInstance();
//        $event->getMobileNumber()
        $result = $sms->send('13269610176', $event->getSmsTemplateId(), $event->getSmsContent());
        dd($result);
    }
}
