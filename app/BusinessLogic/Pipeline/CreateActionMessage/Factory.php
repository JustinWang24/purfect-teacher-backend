<?php


namespace App\BusinessLogic\Pipeline\CreateActionMessage;


use App\BusinessLogic\Pipeline\CreateActionMessage\Impl\EndFlowsLogic;
use App\BusinessLogic\Pipeline\CreateActionMessage\Impl\OngoingFlowsLogic;
use App\BusinessLogic\Pipeline\CreateActionMessage\Impl\StartFlowsLogic;
use App\User;
use App\Utils\Pipeline\INode;
use App\BusinessLogic\Pipeline\CreateActionMessage\Contracts\IActionMessageLogic;

class Factory
{
    /**
     * @param INode $node
     * @param User $user
     * @return IActionMessageLogic
     */
    public static function GetInstance(INode $node, User $user)
    {
        $instance = null;

        if ($node->isHead()) {
            $instance = new StartFlowsLogic($node, $user);
        } elseif ($node->isEnd()) {
            $instance = new EndFlowsLogic;
        } else {
            $instance = new OngoingFlowsLogic;
        }

        return $instance;
    }

}
