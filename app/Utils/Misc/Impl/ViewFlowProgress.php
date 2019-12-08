<?php


namespace App\Utils\Misc\Impl;


use App\User;
use App\Utils\Misc\Contracts\INextAction;
use App\Utils\Pipeline\IFlow;

class ViewFlowProgress implements INextAction
{
    private $flow;
    public function __construct(IFlow $flow)
    {
        $this->flow = $flow;
    }

    public function getActionUrl(User $user)
    {
        return '';
    }
}
