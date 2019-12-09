<?php

namespace App\Listeners\Pipeline\Flow;

use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;
use App\BusinessLogic\Pipeline\Messenger\MessengerFactory;
use App\Events\IFlowAccessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyStarter
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
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
            IMessenger::TYPE_STARTER,
            $event->getUser(), $event->getAction(), $event->getNode(), $event->getFlow()
        );
        $bag = $messenger->handle($event->getAction());
        if(!$bag->isSuccess()){
            Log::error('通知流程发起者错误',['msg'=>$bag->getMessage()]);
        }
    }

    /**
     * 处理失败任务
     *
     * @param  IFlowAccessor  $event
     * @param  \Exception  $exception
     * @return void
     */
//    public function failed(IFlowAccessor $event, $exception)
//    {
//        Log::error($exception->getMessage());
//    }
}
