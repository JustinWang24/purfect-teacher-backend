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
use Illuminate\Support\Facades\Log;

class ForActionStarter extends AbstractMessenger
{
    /**
     * ForActionStarter constructor.
     * @param IFlow $flow
     * @param INode|null $currentNode
     * @param User $user
     */
    public function __construct(IFlow $flow, $currentNode, User $user)
    {
        parent::__construct($flow, $currentNode, $user);
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
            $userFlow = $action->getUserFlow();
            $flowStarter = $userFlow->getUser(); // 流程的发起者

            $content = $flowStarter->getName();
            $flowName = $this->flow->getName();

            // 把查看此流程详情的链接放进去
            $nextMove = route('pipeline.flow-view-history',['action_id'=>$action->id, 'user_flow_id'=>$action->getTransactionId()]);

            if($this->node){

                if($this->node->isHead()){
                    $content .= '成功发起' . $flowName . '流程, 目前进入' . $this->node->getNext()->getName() . '阶段';
                }
                else{
                    // 这个时候, 传入的 node, 是当前步骤
                    if($this->node->getNext()){
                        // 表示还有后面的步骤
                        if($action->isSuccess())
                            $content .= '发起的' . $flowName . '流程, 已经通过了' . $this->node->getName() . '阶段, 目前进入' . $this->node->getNext()->getName().'阶段';
                        else
                            $content .= '发起的' . $flowName . '流程在' . $this->node->getName() . '阶段被'.$this->userOfLastAction->getName().'驳回, 目前在'. $this->node->getPrev()->getName(). '阶段从新处理';
                    }
                    else{
                        // 已经是最后一步了
                        if($userFlow->isDone()){
                            $content .= '发起的' . $flowName . '流程已经获得批准!';
                        }
                        elseif ($userFlow->isTerminated()){
                            $content .= '发起的' . $flowName . '流程被否决了';
                        }
                        elseif (!$action->isSuccess()){
                            $content .= '发起的' . $flowName . '流程被退回到' . $this->node->getPrev()->getName().'阶段';
                        }
                    }
                }
            }

            InternalMessage::dispatchNow(
                SystemNotification::SCHOOL_EMPTY,
                SystemNotification::FROM_SYSTEM,
                $flowStarter->getId(),
                SystemNotification::TYPE_NONE,
                SystemNotification::PRIORITY_LOW,
                $content,
                $nextMove,
                '有一个' . $this->flow->getName() .'流程在等待您处理',
                SystemNotification::COMMON_CATEGORY_PIPELINE //@TODO 工作流程可以区分特殊流程后会拆分不同类型
            );

            if(env('APP_DEBUG', false)){
                Log::info('系统消息', ['msg'=>'给流程发起人发送系统消息:' . $action->getTransactionId()]);
            }

            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }
        catch (\Exception $exception){
            $bag->setMessage( $exception->getMessage() . '; Line:'.$exception->getLine() . ';userFlow:' . $action->getTransactionId() . ';action:'.$action->id);
        }

        return $bag;
    }
}
