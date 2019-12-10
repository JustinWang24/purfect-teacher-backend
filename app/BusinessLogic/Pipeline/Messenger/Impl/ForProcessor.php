<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 11:19 PM
 */

namespace App\BusinessLogic\Pipeline\Messenger\Impl;
use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;
use App\Models\Pipeline\Flow\UserFlow;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\INode;
use App\User;
use App\Utils\Pipeline\IFlow;
use App\Utils\ReturnData\MessageBag;
use App\Utils\JsonBuilder;
use App\Models\Misc\SystemNotification;
use App\Jobs\Notifier\InternalMessage;

class ForProcessor extends AbstractMessenger
{
    /**
     * @var User
     */
    protected $processor;

    /**
     * ForProcessor constructor.
     * @param IFlow $flow
     * @param INode|null $node
     * @param User $user
     */
    public function __construct(IFlow $flow, $node, User $user)
    {
        parent::__construct($flow, $node, $user);
        $this->processor = $user;
    }

    public function handle(IAction $action)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        try{
            $content = $this->processor->getName();
            $flowName = $this->flow->getName();
            /**
             * @var UserFlow $userFlow
             */
            $userFlow = $action->getUserFlow();

            // 把查看此流程详情的链接放进去
            $nextMove = route('pipeline.flow-view-history',['action_id'=>$action->id, 'user_flow_id'=>$action->getTransactionId()]);

            if($action->isSuccess())
                $content .= '通过了' . $userFlow->user_name . '提交的' . $flowName . '流程';
            else
                $content .= '退回了' . $userFlow->user_name . '提交的' . $flowName . '流程';

            InternalMessage::dispatchNow(
                SystemNotification::SCHOOL_EMPTY,
                SystemNotification::FROM_SYSTEM,
                $this->processor->getId(),
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