<?php

namespace App\Listeners\Pipeline\Flow;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\IFlowAccessor;
use App\BusinessLogic\Pipeline\Messenger\MessengerFactory;
use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;
use Illuminate\Support\Facades\Log;

class NotifyProcessor
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
        $messenger = MessengerFactory::GetInstance(
            IMessenger::TYPE_PROCESSOR,
            $event->getUser(), $event->getAction(), $event->getNode(), $event->getFlow()
        );
        $bag = $messenger->handle($event->getAction());
        if(!$bag->isSuccess()){
            Log::error('通知流程发起者错误',['msg'=>$bag->getMessage()]);
        }
    }
}
