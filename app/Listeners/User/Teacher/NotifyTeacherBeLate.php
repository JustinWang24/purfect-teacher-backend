<?php

namespace App\Listeners\User\Teacher;

use App\Events\User\Student\ApproveRegistrationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CanReachByMobilePhone;
use App\Utils\Misc\SmsFactory;
use Illuminate\Support\Facades\Log;

class NotifyTeacherBeLate
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
        $result = $sms->send($event->getMobileNumber(), $event->getSmsTemplateId(), $event->getSmsContent());
        if ($result->getCode() != 0) {
            Log::channel('smslog')->alert('给教务处发送短信事件:'. 'mobile:'. $event->getMobileNumber(). ',templateId:'. $event->getSmsTemplateId(). ',data:'. json_encode($event->getSmsContent()));
        }

    }
}
