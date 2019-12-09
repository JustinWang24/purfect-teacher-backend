<?php


namespace App\BusinessLogic\Pipeline\CreateActionMessage\Impl;

use App\BusinessLogic\Pipeline\CreateActionMessage\Contracts\IActionMessageLogic;
use App\Jobs\Notifier\txt;
use App\Models\Misc\SystemNotification;
use App\Jobs\Notifier\InternalMessage;
use App\Utils\Misc\Impl\ViewFlowProgress;
use App\Utils\Pipeline\IAction;
use App\User;
use App\Utils\Pipeline\INode;
use App\Models\Pipeline\Flow\Node;

/**
 * 开始流程
 * Class StartFlowsLogic
 * @package App\BusinessLogic\Pipeline\CreateActionMessage\Impl
 */
class StartFlowsLogic implements IActionMessageLogic
{
    /**
     * @var INode $node
     */
    private $node;
    private $user;

    public function __construct(INode $node, User $user)
    {
        $this->node = $node;
        $this->user = $user;
    }

    /**
     * @param IAction $action
     */
    public function actionAndMessage(IAction $action)
    {
        /**
         * @var Node $nextStep
         */
        $nextStep = $this->node->getNext(); // 获取下一个步骤

        $users = $nextStep->handler->getNoticeTo();

        // 是否紧急通知
        if ($action->isUrgent()) {
            if(count($users) > 0){
                txt::dispatch(
                    $users,
                    '有新的审批',
                    $this->user->getName().'发起了: '. $action->getFlow()->getName(). '流程, 需要您的审批',
                    new ViewFlowProgress($action->getFlow())
                )->delay(now()->addSecond());
            }
        } else {
            $mes = new InternalMessage(
                SystemNotification::SCHOOL_EMPTY,
                SystemNotification::FROM_SYSTEM,
                $this->user->id,
                SystemNotification::PRIORITY_LOW,
                SystemNotification::TYPE_NONE,
                $this->user->getName().'发起了: '. $action->getFlow()->getName(). '流程, 需要您的审批'
            );
            $mes->handle();

            foreach ($users as $user) {
                new InternalMessage(
                    SystemNotification::SCHOOL_EMPTY,
                    SystemNotification::FROM_SYSTEM,
                    $user->id,
                    SystemNotification::PRIORITY_LOW,
                    SystemNotification::TYPE_NONE,
                    $this->user->getName().'发起了: '. $action->getFlow()->getName(). '流程, 需要您的审批'
                );
                $mes->handle();
            }
        }

    }

}
