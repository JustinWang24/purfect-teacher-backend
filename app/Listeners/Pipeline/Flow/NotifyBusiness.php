<?php

namespace App\Listeners\Pipeline\Flow;

use App\BusinessLogic\Pipeline\Business\BusinessFactory;
use App\Events\IFlowAccessor;
use App\Events\Pipeline\Flow\FlowBusiness;
use Illuminate\Support\Facades\Log;

class NotifyBusiness
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
    public function handle(FlowBusiness $event)
    {
        $business = BusinessFactory::GetInstance($event->getFlow()->business, $event->getUser());
        $bag = $business->handle($event->getOptions());
        if(!$bag->isSuccess()){
            Log::error('业务结果通知失败',['msg'=>$bag->getMessage()]);
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
