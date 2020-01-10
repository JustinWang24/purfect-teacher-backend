<?php

namespace App\Listeners\Send;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CanReachByMobilePhone;
use App\Utils\Misc\SmsFactory;
use Illuminate\Support\Facades\Log;
use App\Jobs\Notifier\Sms as PushSms;

class Sms
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
     * @param CanReachByMobilePhone $event
     * @return void
     */
    public function handle(CanReachByMobilePhone $event)
    {
        PushSms::dispatchNow(
            $event->getMobileNumber(),
            $event->getSmsContent(),
            $event->getSmsTemplateId(),
            $event->getUser(),
            $event->getAction()
        );

        Log::channel('smslog')->alert('发送短信进入队列了');
    }
}
