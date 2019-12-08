<?php

namespace App\Listeners\Pipeline\Flow;

use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;
use App\BusinessLogic\Pipeline\Messenger\MessengerFactory;
use App\Events\IFlowAccessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyStarter implements ShouldQueue
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
     * @return void
     */
    public function handle(IFlowAccessor $event)
    {
//        $logic = Factory::GetInstance($event->getNode(), $event->getUser());
//        $logic->actionAndMessage($event->getAction());

        $messenger = MessengerFactory::GetInstance(
            IMessenger::TYPE_STARTER,
            $event->getFlow(),
            $event->getNode(),
            $event->getUser()
        );
        $bag = $messenger->handle($event->getAction());
        if(!$bag->isSuccess()){
            Log::error('通知流程发起者错误',['msg'=>$bag->getMessage()]);
        }
    }
}
