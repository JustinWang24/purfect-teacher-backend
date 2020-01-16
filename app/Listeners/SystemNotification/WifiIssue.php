<?php

namespace App\Listeners\SystemNotification;

use App\Events\SystemNotification\WifiIssueEvent;
use App\Jobs\Notifier\InternalMessage;
use Illuminate\Support\Facades\Log;

class WifiIssue
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


    public function handle(WifiIssueEvent $event)
    {
        InternalMessage::dispatchNow(
            $event->getSchoolId(),
            $event->getSender(),
            $event->getTo(),
            $event->getType(),
            $event->getPriority(),
            $event->getContent(),
            $event->getNextMove(),
            $event->getTitle(),
            $event->getCategory(),
            $event->getAppExtra()
        );
        Log::channel('systemnotificationlog')->alert('发送系统消息进入队列了');
    }
}
