<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 10:28 PM
 */

namespace App\BusinessLogic\Pipeline\Messenger\Impl;
use App\Jobs\Notifier\InternalMessage;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\Pipeline\INode;
use App\Utils\ReturnData\MessageBag;
use App\Models\Misc\SystemNotification;

class ForActionStarter extends AbstractMessenger
{
    protected $starter;

    public function __construct(IFlow $flow, INode $node, User $user)
    {
        parent::__construct($flow, $node, $user);
        $this->starter = $user;
    }

    /**
     * 这个是发送给流程的发起者的. 通知传入的 action 的结果
     * @param IAction $action
     * @return IMessageBag
     */
    public function handle(IAction $action)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        try{
            $content = $this->starter->getName();
            $flowName = $this->flow->getName();

            // 把查看此流程详情的链接放进去
            $nextMove = route('teacher.pipeline.flow-view-history',['action_id'=>$action->id, 'user_flow_id'=>$action->getTransactionId()]);

            if($this->node->isHead()){
                $content .= '成功发起' . $flowName . '成功, 目前进入' . $this->node->getNext()->getName() . '阶段';
            }
            elseif ($this->node->isEnd()){
                if($action->isSuccess())
                    $content .= '发起的' . $flowName . '流程已经获得批准!';
                else
                    $content .= '发起的' . $flowName . '流程被驳回了';
            }
            else{
                if($action->isSuccess())
                    $content .= '发起的' . $flowName . '流程, 已经通过了' . $this->node->getName() . '阶段, 目前进入' . $this->node->getNext()->getName().'阶段';
                else
                    $content .= '发起的' . $flowName . '流程在' . $this->node->getName() . '阶段被驳回, 目前退回到'. $this->node->getPrev()->getName(). '阶段从新处理';
            }

            InternalMessage::dispatchNow(
                SystemNotification::SCHOOL_EMPTY,
                SystemNotification::FROM_SYSTEM,
                $this->starter->getId(),
                SystemNotification::TYPE_NONE,
                SystemNotification::PRIORITY_LOW,
                $content,
                $nextMove
            );
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }
        catch (\Exception $exception){
            $bag->setMessage( $exception->getMessage() . '; Line:'.$exception->getLine() . ';userFlow:' . $action->getTransactionId() . ';action:'.$action->id);
        }

        return $bag;
    }
}