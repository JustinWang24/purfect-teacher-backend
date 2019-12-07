<?php


namespace App\BusinessLogic\Pipeline\CreateActionMessage\Impl;

use App\BusinessLogic\Pipeline\CreateActionMessage\Contracts\IActionMessageLogic;
use App\Events\IFlowAccessor;

/**
 * 流程进行中
 * Class OngoingFlowsLogic
 * @package App\BusinessLogic\Pipeline\CreateActionMessage\Impl
 */
class OngoingFlowsLogic implements IActionMessageLogic
{

    public function actionAndMessage(IFlowAccessor $event)
    {
        // TODO: Implement actionAndMessage() method.
    }

}
