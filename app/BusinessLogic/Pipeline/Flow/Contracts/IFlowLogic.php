<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:45 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Contracts;

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
     * @param IFlow $flow
     * @return IMessageBag
     */
    public function open(IFlow $flow);

    /**
     * @param IFlowEngine $engine
     * @param array $actionData
     * @return IMessageBag
     */
    public function start(IFlowEngine $engine, $actionData);
}