<?php

namespace App\BusinessLogic\Pipeline\CreateActionMessage\Impl;

use App\BusinessLogic\Pipeline\CreateActionMessage\Contracts\IActionMessageLogic;
use App\Utils\Pipeline\IAction;
use App\Jobs\Notifier\Push;
use App\User;
use App\Utils\Misc\Impl\ViewFlowProgress;


/**
 * 结束流程
 * Class EndFlowsLogic
 * @package App\BusinessLogic\Pipeline\CreateActionMessage\Impl
 */
class EndFlowsLogic implements IActionMessageLogic
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
     * 结束审批直接push通知
     * @param IAction $action
     */
    public function actionAndMessage(IAction $action)
    {
        Push::dispatch(
            $this->user,
            '审批结束',
            '您发起的流程已审批结束,请查看',
            new ViewFlowProgress($action->getFlow())
        )->delay(now()->addSecond());
    }

}
