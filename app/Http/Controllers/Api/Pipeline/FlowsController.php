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
use App\Events\Pipeline\Flow\FlowBusiness;
use App\Events\Pipeline\Flow\FlowProcessed;
use App\Events\Pipeline\Flow\FlowRejected;
use App\Events\Pipeline\Flow\FlowStarted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;
use App\Models\Pipeline\Flow\Action;
use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Node;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use Psy\Util\Json;

class FlowsController extends Controller
{
    /**
     * @param FlowRequest $request
     * @return string
     */
    public function my(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        $types = $logic->getMyFlows(true);
        return JsonBuilder::Success(
            [
                'types'=>$types,
                'url'=>[
                    'base'=>env('APP_URL'),
                    'extra'=>'',
                    'start_flow'=>'/h5/flow/user/start',
                    'view_flows_in_progress'=>'/h5/flow/user/in-progress',
                ],
            ]
        );
    }

    public function business_url(FlowRequest $request) {
        $business = $request->get('business');
        $param = $request->get('param');
        $user = $request->user();
        $dao = $dao = new FlowDao();
        $result =  $dao->getListByBusiness($user->getSchoolId(), $business);
        $retFlow = [];

        foreach ( $result as $flow) {
            if ($dao->checkPermissionByuser($flow, $user, 0)) {
                $retFlow = $flow;
                break;
            }
        }
        if (empty($retFlow)) {
            return JsonBuilder::Error('权限不足');
        }else {
            $param['flow_id'] = $retFlow->id;
            $param['api_token'] = $user->api_token;
            $url = route('h5.flow.user.start', $param);
            return JsonBuilder::Success(['url' => $url]);
        }
    }

    public function open(FlowRequest $request){
        $user = $request->user();
        $flowDao = new FlowDao();
        $flow = $flowDao->getById($request->get('flow_id'));

        if($user && $flow){
            $logic = FlowLogicFactory::GetInstance($user);
            $bag = $logic->open($flow);
            if($bag->isSuccess()){
                $flowInfo = $flow->getSimpleLinkedNodes();
                $handlers = [];
                foreach ($flowInfo['handler'] as $handler) {
                    $tmpHandlers = $flowDao->transTitlesToUser($handler->titles, $handler->organizations, $user);
                    $retHandlers = [];
                    foreach ($tmpHandlers as $tmpKey => $tmpHandler) {
                        foreach ($tmpHandler as $tmp) {
                            $retHandlers[$tmpKey][] = [
                                'id' => $tmp->id,
                                'name' => $tmp->name,
                                'avatar' => $tmp->profile->avatar ?? ''
                            ];
                        }
                    }
                    $handlers[] = $retHandlers;
                }
                if ($flow->business) {
                    $businessOptions = Flow::getBusiness($flow->business);
                    $businessDefault = [];
                    foreach ($businessOptions['options'] as $businessOption) {
                        if ($businessOption['readonly']) {
                            parse_str(parse_url($request->headers->get('referer'), PHP_URL_QUERY), $getParam);
                            $businessDefault[$businessOption['title']] = $getParam[$businessOption['uri']] ?? '';
                        }
                    }
                    $options = [];
                    foreach ($flowInfo['options'] as $option) {
                        if (isset($businessDefault[$option['title']])) {
                            $option['value'] = $businessDefault[$option['title']];
                            $option['default'] = true;
                        }
                        $options[] = $option;
                    }
                }else {
                    $options = $flowInfo['options'];
                }
                $return = [
                    'user' => $user,
                    'flow' => $flow,
                    'handlers' => $handlers,
                    'copys' => $flowInfo['copy'],
                    'options' => $options,
                    'api_token' => $request->get('api_token'),
                    'appName' => 'pipeline-flow-open-app'
                ];
                return JsonBuilder::Success($return);
            }
        }
        return JsonBuilder::Error('您没有权限执行此操作');;
    }

    /**
     * @param FlowRequest $request
     * @return string
     */
    public function started_by_me(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        $position = $request->get('position', 0);
        $keyword = $request->get('keyword');
        $list = $logic->startedByMe($position);
        $retList = [];
        if ($list) {
            foreach ($list as $key => $value) {
                if ($keyword) {
                    if (mb_strpos($value->user->name, $keyword) === false && mb_strpos($value->flow->name, $keyword) === false) {
                        continue;
                    }
                }
                $retList[] = [
                    'id' => $value->id,
                    'avatar' => $value->user->profile->avatar ?? '',
                    'user_name' => $value->user_name,
                    'flow' => ['name' => $value->flow->name ?? ''],
                    'done' => $value->done,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s')
                ];
            }
        }
        return JsonBuilder::Success(['flows'=> $retList]);
    }

    /**
     * @param FlowRequest $request
     * @return string
     */
    public function waiting_for_me(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        $position = $request->get('position', 0);
        $keyword = $request->get('keyword');
        $list = $logic->waitingForMe($position);
        $retList = [];
        if ($list) {
            foreach ($list as $key => $value) {
                if ($keyword) {
                    if (mb_strpos($value->user->name, $keyword) === false && mb_strpos($value->flow->name, $keyword) === false) {
                        continue;
                    }
                }
                $retList[] = [
                    'id' => $value->userFlow->id,
                    'avatar' => $value->userFlow->user->profile->avatar ?? '',
                    'user_name' => $value->userFlow->user_name,
                    'flow' => ['name' => $value->flow->name ?? ''],
                    'done' => $value->userFlow->done,
                    'created_at' => $value->userFlow->created_at->format('Y-m-d H:i:s')
                ];
            }
        }
        return JsonBuilder::Success(['flows'=>$retList]);
    }

    /**
     * @param FlowRequest $request
     * @return string
     */
    public function copy_to_me(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        $position = $request->get('position', 0);
        $keyword = $request->get('keyword');
        $list = $logic->copyToMe($position);
        $retList = [];
        if ($list) {
            foreach ($list as $key => $value) {
                if ($keyword) {
                    if (mb_strpos($value->user->name, $keyword) === false && mb_strpos($value->flow->name, $keyword) === false) {
                        continue;
                    }
                }
                $retList[] = [
                    'id' => $value->id,
                    'avatar' => $value->user->profile->avatar ?? '',
                    'user_name' => $value->user_name,
                    'flow' => ['name' => $value->flow->name ?? ''],
                    'done' => $value->done,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s')
                ];
            }
        }
        return JsonBuilder::Success(['flows'=>$retList]);
    }

    public function my_processed(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        $position = $request->get('position', 0);
        $keyword = $request->get('keyword');
        $list = $logic->myProcessed($position);
        $retList = [];
        if ($list) {
            foreach ($list as $key => $value) {
                if ($keyword) {
                    if (mb_strpos($value->user->name, $keyword) === false && mb_strpos($value->flow->name, $keyword) === false) {
                        continue;
                    }
                }
                $retList[] = [
                    'id' => $value->id,
                    'avatar' => $value->user->profile->avatar ?? '',
                    'user_name' => $value->user_name,
                    'flow' => ['name' => $value->flow->name ?? ''],
                    'done' => $value->done,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s')
                ];
            }
        }
        return JsonBuilder::Success(['flows'=>$retList]);
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
        $actionData['options'] = $request->get('options');
        $user = $request->user();

        $flowDao = new FlowDao();
        $flow = $flowDao->getById($request->getStartFlowId());

        $logic = FlowLogicFactory::GetInstance($user);
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
            return JsonBuilder::Success(
                [
                    'id'=>$action->id,
                    'url'=>$request->isAppRequest()?route('h5.flow.user.view-history',['user_flow_id' => $action->transaction_id,'api_token'=>$request->user()->api_token]):null
                ]
            );
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
        if($action && $action->result == IAction::RESULT_PENDING){
            $logic = FlowLogicFactory::GetInstance($request->user());
            switch ($actionFormData['result']){
                /*case IAction::RESULT_REJECT:
                    $bag = $logic->reject($action, $actionFormData); // 进入驳回流程的操作
                    break;*/
                case IAction::RESULT_TERMINATE:
                    $bag = $logic->terminate($action, $actionFormData); // 进入终止流程的操作
                    break;
                default:
                    $bag = $logic->process($action, $actionFormData); // 进入同意流程的操作, 默认
                    break;
            }

            $event = null;
            if ($bag->isSuccess()){
                switch ($actionFormData['result']){
                    /*case IAction::RESULT_REJECT:
                        // 驳回流程的事件
                        $event = new FlowRejected($request->user(),$action, $bag->getData()['prevNode'], $action->getFlow());
                        break;*/
                    case IAction::RESULT_PASS:
                        if ($dao->getCountWaitProcessUsers($action->getNode()->id, $action->transaction_id) < 1) {
                            //可能存在自动同意已经到了下一个action
                            $newAction = $dao->getActionByUserFlowAndUserId($action->transaction_id, $action->user_id);
                            $flow = $newAction->getFlow();
                            $event = new FlowProcessed($request->user(),$newAction, $newAction->getNode(), $flow);

                            //业务事件
                            if ($newAction->userFlow->isDone() && $flow->business) {
                                  event(new FlowBusiness($flow, $newAction->userFlow));
                            }
                        }
                        break;
                    case IAction::RESULT_TERMINATE:
                        $event = new FlowRejected($request->user(),$action, $action->getNode()->prev, $action->getFlow());
                        break;
                    default:
                        $event = null;
                }

                $event && event($event); // 发布事件
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
