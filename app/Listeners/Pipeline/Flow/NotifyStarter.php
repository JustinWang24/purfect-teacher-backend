<?php

namespace App\Listeners\Pipeline\Flow;

use App\BusinessLogic\Pipeline\CreateActionMessage\Factory;
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
        // Todo: 实现流程审批过程中通知流程发起人的监听器方法
        // 简单判断一下就知道该发送什么消息

        $logic = Factory::GetInstance($event->getNode());
        $logic->actionAndMessage($event->getAction());

        if($event->getNode()->isHead()){
            // 流程启动消息
            Log::info('流程开始111',['msg'=>$event->getUser()->getName().': '.$event->getFlow()->getName().': '.$event->getNode()->getName().' 成功']);
            $logic = new Factory;
            $result = $logic::GetInstance('start');
            $result->actionAndMessage($event);

        } elseif ($event->getNode()->isEnd()){
            // 流程完毕消息
            Log::info('流程结束',['msg'=>$event->getUser()->getName().': '.$event->getFlow()->getName().': '.$event->getNode()->getName().' 已经处理完毕']);
            $logic = new Factory;
            $result = $logic::GetInstance('end');
            $result->actionAndMessage($event);
        } else {
            // 流程处理消息
            if($event->getAction()->isSuccess()){
                // 流程某个步骤通过了
                Log::info('流程流转',
                    ['msg'=>$event->getUser()->getName().': '.$event->getFlow()->getName().': '.$event->getNode()->getName().' 获得通过, 目前进入'.$event->getNode()->next()->getName().'处理中']
                );
            }
            else{
                // 流程某个步骤被驳回
                Log::info('流程开始',
                    ['msg'=>$event->getUser()->getName().': '.$event->getFlow()->getName().': '.$event->getNode()->getName().' 被驳回了, 目前返回'.$event->getNode()->prev()->getName().'重新处理']
                );
            }
        }
    }
}
