<?php
/**
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:40 PM
 */
namespace App\Http\Controllers\Api\Pipeline;
use App\BusinessLogic\Pipeline\Flow\FlowLogicFactory;
use App\Dao\Pipeline\ActionDao;
use App\Dao\Pipeline\FlowDao;
use App\Dao\Pipeline\UserFlowDao;
use App\Events\Pipeline\Flow\FlowProcessed;
use App\Events\Pipeline\Flow\FlowRejected;
use App\Events\Pipeline\Flow\FlowStarted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;
use App\Models\Pipeline\Flow\Action;
use App\Models\Pipeline\Flow\Node;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\IAction;

class FlowsController extends Controller
{
    /**
     * @param FlowRequest $request
     * @return string
     */
    public function my(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        $types = $logic->getMyFlows(true);
        return JsonBuilder::Success(['types'=>$types]);
    }

    /**
     * @param FlowRequest $request
     * @return string
     */
    public function started_by_me(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        return JsonBuilder::Success(['flows'=>$logic->startedByMe()]);
    }

    /**
     * @param FlowRequest $request
     * @return string
     */
    public function waiting_for_me(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        return JsonBuilder::Success(['actions'=>$logic->waitingForMe()]);
    }

    /**
     * 根据一个动作的 id, 配合当前登陆的用户, 来查看整个流程的历史记录
     * @param FlowRequest $request
     * @return string
     */
    public function view_action(FlowRequest $request){
        $actionId = $request->getActionId();
        $userFlowId = $request->getUserFlowId();

        $actionDao = new ActionDao();
        $userFlowDao = new UserFlowDao();

        if($userFlowId){
            $flow = $userFlowDao->getFlowHistory($userFlowId);
        }
        else{
            $action = $actionDao->getByActionId($actionId);
            $flow = $userFlowDao->getFlowHistory($action->getTransactionId());
        }

        return $flow ? JsonBuilder::Success(['flow'=>$flow])
            : JsonBuilder::Error('您没有权限执行此操作');
    }

    /**
     * 启动一个流程的接口
     * @param FlowRequest $request
     * @return string
     */
    public function start(FlowRequest $request){
        $actionData = $request->getStartFlowData();

        $flowDao = new FlowDao();
        $flow = $flowDao->getById($request->getStartFlowId());

        $logic = FlowLogicFactory::GetInstance($request->user());

        $bag = $logic->start(
            $flow,
            $actionData
        );

        if($bag->isSuccess()){
            /**
             * @var Action $action
             */
            $action = $bag->getData()['action'];
            /**
             * @var Node $node
             */
            $node = $bag->getData()['node'];

            // 发布流程启动成功事件
            event(new FlowStarted($request->user(),$action, $flow, $node));
            return JsonBuilder::Success(['id'=>$action->id]);
        }
        else{
            return JsonBuilder::Error($bag->getMessage());
        }
    }

    /**
     * 撤销我发起的流程
     * @param FlowRequest $request
     * @return string
     */
    public function cancel_action(FlowRequest $request){
        $user = $request->user();
        $actionId = $request->getUserFlowId();

        $dao = new ActionDao();
        $bag = $dao->cancelUserFlow($user, $actionId);

        return $bag->isSuccess() ? JsonBuilder::Success() : JsonBuilder::Error($bag->getMessage());
    }

    /**
     * 流程中某个步骤的处理: 通过/驳回
     * @param FlowRequest $request
     * @return string
     */
    public function process(FlowRequest $request){
        $actionFormData = $request->getActionFormData();
        $dao = new ActionDao();
        $action = $dao->getByActionIdAndUserId($actionFormData['id'], $request->user()->id);

        if($action){
            $logic = FlowLogicFactory::GetInstance($request->user());

            switch ($actionFormData['result']){
                case IAction::RESULT_REJECT:
                    $bag = $logic->reject($action, $actionFormData); // 进入驳回流程的操作
                    break;
                case IAction::RESULT_TERMINATE:
                    $bag = $logic->terminate($action, $actionFormData); // 进入终止流程的操作
                    break;
                default:
                    $bag = $logic->process($action, $actionFormData); // 进入同意流程的操作, 默认
                    break;
            }

            if ($bag->isSuccess()){
                switch ($actionFormData['result']){
                    case IAction::RESULT_REJECT:
                        // 驳回流程的事件
                        $event = new FlowRejected($request->user(),$action, $bag->getData()['prevNode'], $action->getFlow());
                        break;
                    case IAction::RESULT_TERMINATE:
                        $event = new FlowRejected($request->user(),$action, $bag->getData()['prevNode'], $action->getFlow());
                        break;
                    default:
                        // 同意流程的事件, 默认
                        $event = new FlowProcessed($request->user(),$action, $bag->getData()['currentNode'], $action->getFlow());
                        break;
                }

                event($event); // 发布事件
                return JsonBuilder::Success();
            }
            else{
                return JsonBuilder::Error($bag->getMessage());
            }
        }
        else{
            return JsonBuilder::Error('你无权进行此操作');
        }
    }
}
