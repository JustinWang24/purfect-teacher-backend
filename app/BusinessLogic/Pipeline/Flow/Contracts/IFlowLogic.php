<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:45 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Contracts;

use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\IFlowEngine;
use App\Utils\ReturnData\IMessageBag;

interface IFlowLogic
{
    /**
     * @return IFlow[]
     */
    public function getMyFlows();

    /**
     * 返回用户发起的流程的第一个动作(发起)的集合
     * @return IAction[]
     */
    public function startedByMe();

    /**
     * 返回等待用户审核的流程的集合
     * @return IAction[]
     */
    public function waitingForMe();

    /**
     * @param IFlow $flow
     * @return IMessageBag
     */
    public function open(IFlow $flow);

    /**
     * @param IFlow $flow
     * @param array $actionData
     * @return IMessageBag
     */
    public function start(IFlow $flow, $actionData);
}