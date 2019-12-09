<?php

namespace App\Events\Pipeline\Flow;

use App\Events\IFlowAccessor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;

class FlowRejected implements IFlowAccessor
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $flow;
    public $prevNode;
    public $user;
    public $action;

    /**
     * FlowProcessed constructor.
     * @param User $user
     * @param IAction $action
     * @param INode $prevNode
     * @param IFlow|null $flow
     */
    public function __construct(User $user, IAction $action, $prevNode, IFlow $flow = null)
    {
        $this->action = $action;
        $this->user = $user;
        $this->flow = $flow;
        $this->prevNode = $prevNode;
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
        return $this->prevNode;
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
