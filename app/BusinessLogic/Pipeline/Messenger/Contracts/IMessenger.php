<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 10:25 PM
 */

namespace App\BusinessLogic\Pipeline\Messenger\Contracts;
use App\Utils\Misc\Contracts\INextAction;
use App\Utils\Pipeline\IAction;
use App\Utils\ReturnData\IMessageBag;

interface IMessenger
{
    const TYPE_STARTER          = 1; // 发起人
    const TYPE_PROCESSOR        = 2; // 执行人
    const TYPE_NEXT_PROCESSORS  = 3; // 下一步的执行人集合

    /**
     * @param IAction $action
     * @return IMessageBag
     */
    public function handle(IAction $action);
}