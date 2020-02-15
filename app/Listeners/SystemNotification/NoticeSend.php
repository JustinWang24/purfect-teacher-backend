<?php

namespace App\Listeners\SystemNotification;

use App\Events\SystemNotification\NoticeSendEvent;
use App\Jobs\Notifier\InternalMessage;
use Illuminate\Support\Facades\Log;

class NoticeSend
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


    public function handle(NoticeSendEvent $event)
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
            $event->getAppExtra(),
            $event->getOrganizationIdArray()
        );
        Log::channel('systemnotificationlog')->alert('发送系统消息进入队列了');
    }
}
