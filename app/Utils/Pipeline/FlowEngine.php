<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:34 PM
 */

namespace App\Utils\Pipeline;

use App\User;

class FlowEngine implements IFlowEngine
{
    const FLOW_MOVED_FORWARD = 1;   // 流程向前了一步
    const FLOW_RESUMED       = 2;   // 流程回退了一步
    const FLOW_END           = 3;   // 流程已经结束

    /**
     * @var IFlow
     */
    protected $flow;
    protected $user;

    public function __construct(IFlow $flow, User $user)
    {
        $this->flow = $flow;
        $this->user = $user;
    }

    public function getFlow(): IFlow
    {
        return $this->flow;
    }

    public function start(INode $headNode, IAction $startAction)
    {
        // TODO: Implement start() method.
    }

    /**
     * 处理流程的方法
     *
     * @return int
     * @throws NodeCanNotBeHandledException
     * @throws NodeHandlerMissingException
     */
    public function process(){
        $action = $this->flow->getCurrentPendingAction($this->user);

        if($action){
            // 表示流程处理正停留在某个步骤:
            /**
             * @var INode $node
             */
            $node = $action->getNode();
            $handler = HandlerFactory::GetHandlerInstance($node);
            if($handler){
                $messageBag = $handler->handle($node);
                if($messageBag->isSuccess()){
                    if(!$node->isEnd()){
                        // 表示整个流程已经完成了
                        return self::FLOW_END;
                    }
                    else{
                        // 表示不是最后一个步骤, 那么开启下一步
                        $this->flow->setCurrentPendingNode($node->next(), $this->user);
                        return self::FLOW_MOVED_FORWARD;
                    }
                }
                else{
                    throw new NodeCanNotBeHandledException($messageBag->getMessage());
                }
            }
            else{
                throw new NodeHandlerMissingException('没有找到' .$node->getName() .'步骤对应的处理方法');
            }
        }
    }

    /**
     * 返回上一步: 驳回
     *
     * @return int
     * @throws NodeCanNotBeHandledException
     * @throws NodeHandlerMissingException
     */
    public function resume(){
        $action = $this->flow->getCurrentPendingAction($this->user);

        if($action){
            $node = $action->getNode();
            // 表示流程处理正停留在某个步骤的前一个步骤
            $handler = HandlerFactory::GetHandlerInstance($node);
            if($handler){
                $messageBag = $handler->resume($node);
                if($messageBag->isSuccess()){
                    if(!$node->isHead()){
                        // 如果被驳回的流程不是整个流程的第一步
                        $this->flow->setCurrentPendingNode(
                            $node->prev(),
                            $node->prev()->getLastAction()->getUser()
                        );
                        return self::FLOW_RESUMED;
                    }
                    else{
                        // Todo: 如果第一步就被驳回, 如何处理?
                    }
                }
                else{
                    throw new NodeCanNotBeHandledException($messageBag->getMessage());
                }
            }
            else{
                throw new NodeHandlerMissingException('没有找到' .$node->getName() .'步骤对应的处理方法');
            }
        }

        // 表示整个流程已经完成了
        return self::FLOW_RESUMED;
    }
}