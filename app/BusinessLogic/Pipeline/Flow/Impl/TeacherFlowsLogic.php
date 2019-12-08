<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Impl;


use App\BusinessLogic\Pipeline\Flow\Contracts\IFlowLogic;
use App\Dao\Pipeline\ActionDao;
use App\Dao\Pipeline\FlowDao;
use App\Models\Teachers\Teacher;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\IFlowEngine;
use App\Utils\Pipeline\INode;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class TeacherFlowsLogic implements IFlowLogic
{
    /**
     * @var User $user
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 获取教师的专用流程
     * @return IFlow[]|array
     */
    public function getMyFlows()
    {
        $dao = new FlowDao();
        return $dao->getGroupedFlows(
            $this->user->getSchoolId(),Teacher::FlowTypes()
        );
    }

    /**
     * 获取我发起的流程
     * @return IAction[]|\Illuminate\Database\Eloquent\Collection
     */
    public function startedByMe()
    {
        $actionDao = new ActionDao();
        return $actionDao->getFlowsWhichStartBy($this->user);
    }

    public function waitingForMe()
    {
        $actionDao = new ActionDao();
        return $actionDao->getFlowsWaitingFor($this->user);
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

            $dao = new ActionDao();
            DB::beginTransaction();
            try{
                $action = $dao->create($actionData);
                $nextNode = $startNode->getNext();
                if($nextNode){
                    // 生成下一步需要的操作
                    $handler = $nextNode->getHandler();
                    if($handler){
                        // 根据当前提交 action 的用户和流程, 创建所有需要的下一步流程
                        $handler->handle($this->user, $flow);
                    }
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
}