<?php


namespace App\BusinessLogic\Pipeline\CreateActionMessage\Impl;

use App\BusinessLogic\Pipeline\CreateActionMessage\Contracts\IActionMessageLogic;
use App\Utils\Pipeline\IAction;
use App\Models\Misc\SystemNotification;
use App\Utils\Misc\Impl\ViewFlowProgress;
use App\Jobs\Notifier\InternalMessage;

/**
 * 流程进行中
 * Class OngoingFlowsLogic
 * @package App\BusinessLogic\Pipeline\CreateActionMessage\Impl
 */
class OngoingFlowsLogic implements IActionMessageLogic
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
        if ($action->isSuccess()) {
            $title = '您的发起的审批通过';
            $content = $action->getFlow()->getName(). ': '. $action->getNode()->getName(). '已通过, 目前进入'. $action->getNode()->getNext()->getName(). '处理中';
        } else {
            $title = '您的发起的审批被驳回了';
            $content = $action->getFlow()->getName(). ': '. $action->getNode()->getName(). '被驳回了, 目前返回'. $action->getNode()->prev()->getName(). '请重新处理';
        }

        if ($action->isUrgent()) {
            Push::dispatch(
                $this->user,
                $title,
                $content,
                new ViewFlowProgress($action->getFlow())
            )->delay(now()->addSecond());
        } else {
            $mes = new InternalMessage(
                0,
                0,
                $this->user->id,
                SystemNotification::PRIORITY_LOW,
                SystemNotification::TYPE_NONE,
                $content
            );
            $mes->handle();
        }
    }

}
