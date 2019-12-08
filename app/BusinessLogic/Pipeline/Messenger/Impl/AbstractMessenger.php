<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 11:51 PM
 */

namespace App\BusinessLogic\Pipeline\Messenger\Impl;
use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;
use App\Utils\Misc\Contracts\INextAction;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\INode;
use App\User;
use App\Utils\Pipeline\IFlow;

abstract class AbstractMessenger implements IMessenger, INextAction
{
    /**
     * @var INode
     */
    protected $node;

    /**
     * @var IFlow
     */
    protected $flow;

    public function __construct(IFlow $flow, INode $node, User $user)
    {
        $this->node = $node;
        $this->flow = $flow;
    }

    public abstract function handle(IAction $action);

    public function getActionUrl()
    {
        // TODO: Implement getActionUrl() method.
    }

    public function getActionData()
    {
        // TODO: Implement getActionData() method.
    }
}