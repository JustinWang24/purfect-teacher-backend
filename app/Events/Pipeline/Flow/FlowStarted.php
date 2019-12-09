<?php

namespace App\Events\Pipeline\Flow;

use App\Events\IFlowAccessor;
use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FlowStarted implements IFlowAccessor
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flow;
    public $currentNode;
    public $user;
    public $action;

    /**
     * FlowStarted constructor.
     * @param IFlow $flow
     * @param User $user
     * @param IAction $action
     * @param INode|null $currentNode
     */
    public function __construct(User $user, IAction $action, IFlow $flow = null, $currentNode = null)
    {
        $this->flow = $flow;
        $this->user = $user;
        $this->action = $action;
        $this->currentNode = $currentNode;
    }

    public function getFlow(): IFlow
    {
        if(!$this->flow){
            $this->flow = $this->action->getFlow();
        }

        return $this->flow;
    }

    public function getNode()
    {
        if(!$this->currentNode){
            $this->currentNode = $this->action->getNode();
        }
        return $this->currentNode;
    }

    public function getAction(): IAction
    {
        return $this->action;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
