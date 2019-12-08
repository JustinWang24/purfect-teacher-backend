<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 11:50 PM
 */

namespace App\BusinessLogic\Pipeline\Messenger\Impl;
use App\Jobs\Notifier\InternalMessage;
use App\Jobs\Notifier\Push;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use App\User;
use App\Models\Misc\SystemNotification;

class ForActionNextProcessors extends AbstractMessenger
{
    private $currentUser;

    public function __construct(IFlow $flow, INode $node, User $user)
    {
        parent::__construct($flow, $node, $user);
        $this->currentUser = $user;
    }

    public function handle(IAction $action)
    {
        $handler = $this->node->getHandler();
        if($handler){
            $users = $handler->getNoticeTo($this->currentUser);

            $content = $this->currentUser->getName();

            if($action->isUrgent()){
                // 紧急, 利用 APP 的消息通知下一步的处理人员
                $title = '有一个' . $this->flow->getName() .'流程在等待您处理';

                if($action->isSuccess()){
                    $content .= '通过了' . $this->flow->getName() .'的'.$this->node->getName().'阶段';
                }
                else{
                    $content .= '驳回了' . $this->flow->getName() .'的'.$this->node->getName().'阶段';
                }

                Push::dispatch(
                    $users, $title, $content, $this->getActionUrl()
                )->delay(now()->addSecond());
            }
            else{
                foreach ($users as $user) {
                    /**
                     * @var User $user
                     */
                    InternalMessage::dispatch(
                        SystemNotification::SCHOOL_EMPTY,
                        SystemNotification::FROM_SYSTEM,
                        $user->getId(),
                        SystemNotification::TYPE_NONE,
                        SystemNotification::PRIORITY_LOW,
                        $content,
                        $this->getActionUrl()
                    );
                }
            }
        }
    }
}