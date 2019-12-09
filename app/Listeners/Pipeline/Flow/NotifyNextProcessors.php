<?php

namespace App\Listeners\Pipeline\Flow;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\IFlowAccessor;
use App\BusinessLogic\Pipeline\Messenger\MessengerFactory;
use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;

class NotifyNextProcessors
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
     * @param  IFlowAccessor  $event
     * @return boolean
     */
    public function handle(IFlowAccessor $event)
    {
        $messenger = MessengerFactory::GetInstance(
            IMessenger::TYPE_NEXT_PROCESSORS,
            $event->getUser(), $event->getAction(), $event->getNode(), $event->getFlow()
        );
        $messenger->handle($event->getAction());
        return true;
    }
}
