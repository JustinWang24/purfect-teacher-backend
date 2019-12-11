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
     * @var INode: 永远是当前的被执行的步骤节点
     */
    protected $node;

    /**
     * @var IFlow
     */
    protected $flow;

    protected $userOfLastAction;

    /**
     * AbstractMessenger constructor.
     * @param IFlow $flow
     * @param INode|null $node
     * @param User $user
     */
    public function __construct(IFlow $flow, $node, User $user)
    {
        $this->node = $node;
        $this->flow = $flow;
        $this->userOfLastAction = $user;
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