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

            //$content = $flowStarter->getName();
            $content = '';
            $flowName = $this->flow->getName();

            // 把查看此流程详情的链接放进去
            $nextMove = route('h5.flow.user.view-history',['action_id'=>$action->id, 'user_flow_id'=>$action->getTransactionId()]);
            if($userFlow->isDone()){
                $title = '你的' . $flowName .'已经通过了审批';
            }
            elseif ($userFlow->isTerminated()){
                $content .= '原因：' . $action->content;
                $title = '你的' . $flowName .'未通过审批';
            }
            else {
                $content .= '成功发起' . $flowName . '流程, 目前进入' . $this->node->getNext()->getName() . '阶段';
                $title = '你的' . $flowName .'有新的进展!';
            }

            $category = SystemNotification::getCategoryByPipelineTypeAndBusiness($this->flow->type, $this->flow->business, $flowStarter->isStudent());
            InternalMessage::dispatchNow(
                $flowStarter->getSchoolId(),
                SystemNotification::FROM_SYSTEM,
                $flowStarter->getId(),
                SystemNotification::TYPE_NONE,
                SystemNotification::PRIORITY_LOW,
                $content,
                $nextMove,
                $title,
                $category,
                json_encode(['type' =>"web-view", 'param1' => $nextMove, 'param2' => ''])
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
