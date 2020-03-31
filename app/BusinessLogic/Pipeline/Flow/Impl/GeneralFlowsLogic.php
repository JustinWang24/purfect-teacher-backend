<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 4:05 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;

use App\BusinessLogic\Pipeline\Flow\Contracts\IFlowLogic;
use App\BusinessLogic\Pipeline\Flow\FlowLogicFactory;
use App\Dao\Misc\SystemNotificationDao;
use App\Dao\Pipeline\UserFlowDao;
use App\Models\Pipeline\Flow\Action;
use App\Models\Pipeline\Flow\Copys;
use App\Models\Pipeline\Flow\Node;
use App\Models\Pipeline\Flow\UserFlow;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\IUserFlow;
use App\User;
use App\Dao\Pipeline\ActionDao;
use App\Utils\ReturnData\MessageBag;
use App\Utils\JsonBuilder;
use Illuminate\Support\Facades\DB;
use App\Utils\Pipeline\INode;
use App\Utils\ReturnData\IMessageBag;
use App\Models\Misc\SystemNotification;

abstract class GeneralFlowsLogic implements IFlowLogic
{
    /**
     * @var User $user
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public abstract function getMyFlows($forApp = false);

    /**
     * 获取所有等待用户处理的操作
     * @return IAction[]|\Illuminate\Database\Eloquent\Collection
     */
    public function waitingForMe($position = 0, $keyword= '', $size= '')
    {
        $actionDao = new ActionDao();
        return $actionDao->getFlowsWaitingFor($this->user, $position, $keyword, $size);
    }

    /**
     * 获取抄送我的流程
     * @return mixed
     */
    public function copyToMe($position = 0, $keyword= '', $size= '')
    {
        $actionDao = new ActionDao();
        return $actionDao->getFlowsWhichCopyTo($this->user, $position, $keyword, $size);
    }

    public function myProcessed($position = 0, $keyword= '', $size= '')
    {
        $actionDao = new ActionDao();
        return $actionDao->getFlowsWhichMyProcessed($this->user, $position, $keyword, $size);
    }

    /**
     * 获取我发起的流程
     * @return IAction[]|\Illuminate\Database\Eloquent\Collection
     */
    public function startedByMe($position = 0, $keyword= '', $size= '')
    {
        $actionDao = new ActionDao();
        return $actionDao->getFlowsWhichStartBy($this->user, $position, $keyword, $size);
    }

    /**
     * @param IFlow $flow
     * @return IMessageBag
     */
    public function open(IFlow $flow)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        if($headNode = $flow->canBeStartBy($this->user)){
            $bag->setData([
                'node'=>$headNode
            ]);
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }else{
            $bag->setMessage('用户没有权限打开本流程');
        }
        return $bag;
    }

    /**
     * 启动流程的逻辑
     * @param IFlow $flow
     * @param array $actionData
     * @return IMessageBag
     */
    public function start(IFlow $flow, $actionData)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        /**
         * @var INode $startNode
         */
        $startNode = $flow->canBeStartBy($this->user);

        if($startNode){
            // 用户可以启动流程, 那么就获得了启动流程的第一个步骤节点
            $actionData['node_id'] = $startNode->id;
            $actionData['user_id'] = $this->user->id;
            $actionData['result'] = IAction::RESULT_PASS; // 启动工作没有审核, 直接就是 pass 的状态
            //@TODO 不启用加急功能
            $actionData['urgent'] = 0;
            $actionData['is_start'] = 1;

            $dao = new ActionDao();
            DB::beginTransaction();
            try{
                // 先为当前的用户创建一个用户流程
                $userFlow = UserFlow::create([
                    'flow_id'=>$flow->id,
                    'user_id'=>$this->user->id,
                    'user_name'=>$this->user->getName(),
                    'done'=>IUserFlow::IN_PROGRESS,
                ]);

                // 用户流程创建之后, 创建该流程的第一个 action
                $action = $dao->create($actionData, $userFlow);

                // 第一个 action 创建成功后, 找到流程的第二步, 然后针对第二步所依赖的审批人(handlers), 创建需要执行的 actions
                // 生成下一步需要的操作
                $handler = $startNode->getHandler();
                if($handler){
                    // 根据当前提交 action 的用户和流程, 创建所有需要的下一步流程
                    $handler->handle($this->user, $action, $startNode->getNext());
                }

                $bag->setCode(JsonBuilder::CODE_SUCCESS);
                $bag->setData([
                    'action'=>$action,
                    'node'=>$startNode,
                ]);
                DB::commit();
            }
            catch (\Exception $exception){
                $bag->setMessage($exception->getMessage().', line='.$exception->getLine());
                DB::rollBack();
            }
        }
        else{
            $bag->setMessage('用户没有权限启动本流程');
        }
        return $bag;
    }

    /**
     * 同意
     * @param IAction $action
     * @param $actionData
     * @return \App\Utils\ReturnData\IMessageBag
     */
    public function process(IAction $action, $actionData)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        if($action){
            /**
             * @var INode $node
             */
            $node = $action->getNode();
            if($node){
                DB::beginTransaction();
                try{
                    $action->result = $actionData['result'];
                    $action->content = $actionData['content'];
                    $action->urgent = $actionData['urgent'];
                    $action->save();

                    $actionDao = new ActionDao();
                    if ($actionDao->getCountWaitProcessUsers($node->id, $action->getTransactionId()) > 0) {
                        //当前流程还需别人审批

                    }else {
                        $next = $node->getNext();
                        if($next){
                            $userFlowDao = new UserFlowDao();
                            $userFlow = $userFlowDao->getById($action->getTransactionId());
                            $handler = $node->getHandler();
                            $handler->handle($userFlow->user, $action, $next);
                            //自动同意
                            $this->autoProcessed($action->flow, $action, $next, $actionData);
                        }
                        else{
                            // 已经是流程的最后一步, 标记流程已经完成
                            $userFlowDao = new UserFlowDao();
                            // 整个流程被通过了
                            $userFlowDao->update($action->getTransactionId(),['done'=>IUserFlow::DONE]);

                            //添加抄送人
                            if ($action->flow->copy_uids) {
                                $userIdArr = explode(';', trim($action->flow->copy_uids, ';'));
                                foreach ($userIdArr as $userid) {
                                    Copys::create([
                                        'user_id' => $userid,
                                        'user_flow_id' => $action->getTransactionId()
                                    ]);
                                }
                            }


                            //是否触发事件 @TODO 系统流程审批通过在这里定义事件
                            switch ($action->flow->business) {
                                case IFlow::BUSINESS_TYPE_MACADDRESS :
                                    $event = '';
                                    break;
                                default:
                                    $event = null;
                                    break;
                            }
                            if ($event) {
                                event($event);
                            }
                        }
                    }

                    DB::commit();
                    $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                    $messageBag->setData(['nextNode'=>$next,'currentNode'=>$node]);
                }
                catch (\Exception $exception){
                    DB::rollBack();
                    $messageBag->setMessage($exception->getMessage());
                }
            }
            else{
                $messageBag->setMessage('没有找到对应的步骤');
            }
        }
        return $messageBag;
    }

    public function autoProcessed($flow, $action, $next, $formData){
        if (!$flow->auto_processed || !$next) {
            return false;
        }
        $actionDao = new ActionDao();
        $nextAction = $actionDao->getActionByUserFlowAndUserId($action->transaction_id, $action->user_id);
        if ($nextAction && $nextAction->result == IAction::RESULT_PENDING) {
            $logic = FlowLogicFactory::GetInstance($action->user);
            return $logic->process($nextAction, $formData);
        }

        return false;
    }

    /**
     * 驳回 @TODO 不要了 直接终止流程
     * @param IAction $action
     * @param $actionData
     * @return \App\Utils\ReturnData\IMessageBag
     */
    public function reject(IAction $action, $actionData)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        if($action){
            /**
             * @var INode $node
             */
            $node = $action->getNode();
            if($node){
                DB::beginTransaction();
                try{
                    $action->result = $actionData['result'];
                    $action->content = $actionData['content'];
                    $action->urgent = $actionData['urgent'];
                    $action->save();

                    // Todo:  这里的逻辑不对, 要尽快修复
                    $handler = $node->getPrev()->getHandler();
                    $handler->handle($this->user, $action, $node);

                    DB::commit();
                    $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                }
                catch (\Exception $exception){
                    DB::rollBack();
                    $messageBag->setMessage($exception->getMessage());
                }
            }
            else{
                $messageBag->setMessage('没有找到对应的步骤');
            }
        }
        return $messageBag;
    }

    public function terminate(IAction $action, $actionData)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        if($action){
            /**
             * @var INode $node
             */
            $node = $action->getNode();
            if($node){
                DB::beginTransaction();
                try{
                    $action->result = $actionData['result'];
                    $action->content = $actionData['content'];
                    $action->urgent = $actionData['urgent'];
                    $action->save();

                    // 将整个流程终止
                    $userFlowDao = new UserFlowDao();
                    $userFlowDao->terminate($action->getTransactionId());

                    //删除同一级别的其他人
                    Action::where(['transaction_id' => $action->getTransactionId(),'node_id' => $action->node_id, 'result' => Action::RESULT_PENDING])->delete();

                    DB::commit();
                    $messageBag->setCode(JsonBuilder::CODE_SUCCESS);

                }
                catch (\Exception $exception){
                    DB::rollBack();
                    $messageBag->setMessage($exception->getMessage());
                }
            }
            else{
                $messageBag->setMessage('没有找到对应的步骤');
            }
        }
        return $messageBag;
    }
}
