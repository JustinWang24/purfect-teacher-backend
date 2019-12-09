<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 10:25 PM
 */

namespace App\BusinessLogic\Pipeline\Messenger;
use App\BusinessLogic\Pipeline\Messenger\Impl\ForActionNextProcessors;
use App\BusinessLogic\Pipeline\Messenger\Impl\ForActionStarter;
use App\BusinessLogic\Pipeline\Messenger\Impl\ForProcessor;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use App\User;
use App\BusinessLogic\Pipeline\Messenger\Contracts\IMessenger;

class MessengerFactory
{
    /**
     * @param int $type: 类型
     * @param User $user: 当前步骤的操作者
     * @param IAction $action: 动作
     * @param INode $node: 步骤
     * @param IFlow|null $flow: 流程
     * @return IMessenger
     */
    public static function GetInstance($type, $user, $action, $node, $flow = null)
    {
        /**
         * @var IMessenger $instance
         */
        $instance = null;
        switch ($type){
            case IMessenger::TYPE_STARTER:
                $instance = new ForActionStarter($flow, $node, $user);
                break;
            case IMessenger::TYPE_PROCESSOR:
                $instance = new ForProcessor($flow, $node, $user);
                break;
            case IMessenger::TYPE_NEXT_PROCESSORS:
                $instance = new ForActionNextProcessors($flow, $node, $user);
                break;
            default:
                break;
        }
        return $instance;
    }
}