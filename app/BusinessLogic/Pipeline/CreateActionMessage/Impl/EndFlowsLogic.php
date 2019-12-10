<?php

namespace App\BusinessLogic\Pipeline\CreateActionMessage\Impl;

use App\BusinessLogic\Pipeline\CreateActionMessage\Contracts\IActionMessageLogic;
use App\Events\IFlowAccessor;

/**
 * 结束流程
 * Class EndFlowsLogic
 * @package App\BusinessLogic\Pipeline\CreateActionMessage\Impl
 */
class EndFlowsLogic implements IActionMessageLogic
{

    public function actionAndMessage(IFlowAccessor $event)
    {
        // TODO: Implement actionAndMessage() method.
    }

}
