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
use Illuminate\Support\Facades\Log;

class ForActionNextProcessors extends AbstractMessenger
{
    private $currentUser;

    /**
     * ForActionNextProcessors constructor.
     * @param IFlow $flow
     * @param INode|null $node
     * @param User $user
     */
    public function __construct(IFlow $flow, $node, User $user)
    {
        parent::__construct($flow, $node, $user);
        $this->currentUser = $user;
    }

    public function handle(IAction $action)
    {
        $handler = $this->node ? $this->node->getHandler() : null;
        if($handler){
            $users = $handler->getNoticeTo($action->getUserFlow()->getUser());
            $content = '申请人：'. $this->currentUser->getName();

            /*if($this->node && $this->node->isHead()){
                $content .= '提交 "' . $this->flow->getName() .'" 申请等您审核';
            }
            else{
                if($action->isSuccess()){
                    $content .= '通过了' . $this->flow->getName() .'的'.$this->node->getName().'阶段';
                }
                else{
                    $content .= '驳回了' . $this->flow->getName() .'的'.$this->node->getName().'阶段';
                }
            }*/

            $nextMove = route('h5.flow.user.view-history',['action_id'=>$action->id, 'user_flow_id'=>$action->getTransactionId()]);
            if($action->isUrgent()){
                // 紧急, 利用 APP 的消息通知下一步的处理人员
                $title = '有一个' . $this->flow->getName() .'流程在等待您处理';
              $pushData = ['noticedTo'=>$users, 'title'=>$title, 'content'=>$content, 'nextStep'=>$nextMove];
              Push::dispatch($pushData)
                ->delay(now());
            }

            $category = SystemNotification::getCategoryByPipelineTypeAndBusiness($this->flow->type, $this->flow->business, false);
            foreach ($users as $user) {
                /**
                 * @var User $user
                 */
                InternalMessage::dispatchNow(
                    $user->getSchoolId(),
                    SystemNotification::FROM_SYSTEM,
                    $user->getId(),
                    SystemNotification::TYPE_NONE,
                    SystemNotification::PRIORITY_LOW,
                    $content,
                    $nextMove,
                    '有一个' . $this->flow->getName() .'流程在等待您处理',
                    $category,
                    json_encode(['type' =>"web-view", 'param1' => $nextMove, 'param2' => ''])
                );

                if(env('APP_DEBUG', false)){
                    Log::info('系统消息', ['msg'=>'给流程审核人: '.$user->getId().', 发送系统消息:' . $action->getTransactionId(),'class'=>self::class]);
                }
            }
        }
    }
}
