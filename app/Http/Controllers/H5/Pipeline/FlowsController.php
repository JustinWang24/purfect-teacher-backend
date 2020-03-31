<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 12/12/19
 * Time: 6:17 PM
 */
namespace App\Http\Controllers\H5\Pipeline;

use App\Dao\Pipeline\FlowDao;
use App\Http\Controllers\Controller;
use App\Models\NetworkDisk\Media;
use App\Models\Pipeline\Flow\ActionOption;
use App\Models\Pipeline\Flow\UserFlow;
use App\User;
use App\Utils\Pipeline\IUserFlow;
use Illuminate\Http\Request;
use App\BusinessLogic\Pipeline\Flow\FlowLogicFactory;
use App\Dao\Pipeline\ActionDao;
use App\Utils\Pipeline\IAction;

class FlowsController extends Controller
{
    /**
     * 用户启动一个流程
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function start(Request $request){
        $this->dataForView['pageTitle'] = '发起审批';

        /**
         * @var User $user
         */
        $user = $request->user('api');

        $flowDao = new FlowDao();
        $flow = $flowDao->getById($request->get('flow_id'));

        if($user && $flow){
            $logic = FlowLogicFactory::GetInstance($user);
            $bag = $logic->open($flow);
            if($bag->isSuccess()){
                $flowInfo = $flow->getSimpleLinkedNodes();
                $handlers = [];
                foreach ($flowInfo['handler'] as $handler) {
                    $handlers[] = $flowDao->transTitlesToUser($handler->titles, $handler->organizations, $user);
                }
                $this->dataForView['user'] = $user;
                $this->dataForView['flow'] = $flow;
                $this->dataForView['handlers'] = $handlers;
                $this->dataForView['node'] = $bag->getData()['node'];
                $this->dataForView['api_token'] = $request->get('api_token');
                $this->dataForView['appName'] = 'pipeline-flow-open-app';
                return view('h5_apps.pipeline.flow_open',$this->dataForView);
            }
        }
        return '您无权使用本流程';
    }

    public function waiting_for_me(Request $request) {
        $this->dataForView['pageTitle'] = '待我审批';
        $user = $request->user('api');
        if ($user) {
            $position = $request->get('position', 0);
            $this->dataForView['position'] = $position;
            $this->dataForView['user'] = $user;
            $this->dataForView['api_token'] = $request->get('api_token');
            return view('h5_apps.pipeline.flow_waiting_for_me', $this->dataForView);
        }
        return '您无权使用本流程';
    }

    public function my_processed(Request $request) {
        $this->dataForView['pageTitle'] = '我审批的';
        $user = $request->user('api');
        if ($user) {
            $position = $request->get('position', 0);
            $this->dataForView['position'] = $position;
            $this->dataForView['user'] = $user;
            $this->dataForView['api_token'] = $request->get('api_token');
            return view('h5_apps.pipeline.flow_my_processed', $this->dataForView);
        }
        return '您无权使用本流程';
    }

    public function copy_to_me(Request $request) {
        $this->dataForView['pageTitle'] = '抄送我的';
        $user = $request->user('api');
        if ($user) {
            $position = $request->get('position', 0);
            $this->dataForView['position'] = $position;
            $this->dataForView['user'] = $user;
            $this->dataForView['api_token'] = $request->get('api_token');
            return view('h5_apps.pipeline.flow_copy_to_me', $this->dataForView);
        }
        return '您无权使用本流程';
    }

    public function in_progress(Request $request){
        $this->dataForView['pageTitle'] = '我发起的';
        /**
         * @var User $user
         */
        $user = $request->user('api');

        if($user){
            $position = $request->get('position', 0);
            $this->dataForView['user'] = $user;
            $this->dataForView['position'] = $position;
            $this->dataForView['api_token'] = $user->api_token;
            $this->dataForView['appName'] = 'pipeline-flows-in-progress';
            return view('h5_apps.pipeline.flow_in_progress',$this->dataForView);
        }
        else{
            return '您无权使用本功能';
        }
    }

    /**
     * 用户查看流程历史记录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function view_history(Request $request){
        $this->dataForView['pageTitle'] = '审批详情';
        /**
         * @var User $user
         */
        $user = $request->user('api');
        $userFlowId = $request->get('user_flow_id', null);

        if($user && $userFlowId){
            $dao = new ActionDao();
            //发布者action
            $startUserAction = $dao->getFirstActionByUserFlow($userFlowId);
            //当前用户action
            $nowUserAction = $dao->getActionByUserFlowAndUserId($userFlowId, $user->id);
            $this->dataForView['showActionEditForm'] = !empty($nowUserAction)
                && empty($request->get('readonly', '0'))
                && $nowUserAction->result == IAction::RESULT_PENDING
                && $startUserAction->userFlow->done == IUserFlow::IN_PROGRESS  ? true : false;

            $flowDao = new FlowDao();
            //流程信息
            $flow = $flowDao->getById($startUserAction->flow_id);
            $flowInfo = $flow->getSimpleLinkedNodes();
            $handlers = [];
            //审批结果
            $actionResult = $dao->getHistoryByUserFlow($startUserAction->userFlow->id, true);
            $actionReList = [];
            foreach ($actionResult as $actRet) {
                $actionReList[$actRet->node_id . '_' .$actRet->user_id] = $actRet;
            }
            $handlersIcon = [];
            //审批人与结果关联
            foreach ($flowInfo['handler'] as $handler) {
                $icon = '';
                $userList = $flowDao->transTitlesToUser($handler->titles, $handler->organizations, $startUserAction->userFlow->user);
                foreach ($userList as $item) {
                    foreach ($item as $im) {
                        if (isset($actionReList[$handler->node_id.'_'.$im->id])) {
                            $im->result = $actionReList[$handler->node_id.'_'.$im->id];
                            //如果有人拒绝整个流程都是拒绝
                            if ($im->result->result == IAction::RESULT_TERMINATE) {
                                $icon = 'error';
                            }
                            if ($im->result->result == IAction::RESULT_REJECT) {
                                $icon = 'error';
                            }
                            //如果有人等待 整个流程都是等待
                            if (empty($icon) && $im->result->result == IAction::RESULT_PENDING) {
                                $icon = 'pending';
                            }
                        }else {
                            //如果还没轮到 整个流程都是等待
                            $icon = 'wait';
                            $im->result = [];
                        }
                    }
                }
                if (empty($icon)) {
                    $icon = 'success';
                }
                $handlersIcon[] = $icon;
                $handlers[] = $userList;
            }
            //表单信息
            $optionReList = [];
            foreach ($flowInfo['options'] as $option) {
                if ($option['type'] == 'node') {
                  continue;
                }
                $optionRet = ActionOption::where('action_id', $startUserAction->id)->where('option_id', $option['id'])->value('value');
                $value = '';
                switch ($option['type']) {
                    case 'date-date':
                        if ($optionRet) {
                            $optionRet = explode('~', $optionRet);
                            $value = $optionRet[0];
                            if (!empty($optionRet[1])) {
                                $optionReList[] = [
                                    'type' => $option['type'],
                                    'name' => $option['name'],
                                    'title' => $option['title'],
                                    'value' => $value
                                ];

                                $option['title'] = $option['extra']['title2'];
                                $value = $optionRet[1];
                            }
                        }
                        break;
                    case 'radio':
                        if ($optionRet) {
                            $optionRet = json_decode($optionRet, true);
                            if (!empty($optionRet)) {
                                $value = $optionRet['itemText'];
                            }
                        }
                        break;
                    case 'checkbox':
                        if ($optionRet) {
                            $optionRet = json_decode($optionRet, true);
                            if (!empty($optionRet)) {
                                foreach ($optionRet as $ret) {
                                    $value .= ' ' . $ret['itemText'];
                                }
                            }
                        }
                        break;

                    case 'image':
                        if ($optionRet) {
                            $value = explode(',', $optionRet);
                        }
                        break;
                    case 'files':
                        if ($optionRet) {
                            $value = Media::whereIn('id', explode(',', $optionRet))->select(['file_name','url'])->get()->toArray();
                        }
                        break;
                    default:
                        $value = $optionRet;
                        break;
                }

                $optionReList[] = [
                    'type' => $option['type'],
                    'name' => $option['name'],
                    'title' => $option['title'],
                    'value' => $value
                ];
            }
            $this->dataForView['flowInfo'] = $flowInfo;
            $this->dataForView['handlers'] = $handlers;
            $this->dataForView['options'] = $optionReList;
            $this->dataForView['copys'] = $flowInfo['copy'];
            $this->dataForView['startUser'] = $startUserAction->userFlow->user;
            $this->dataForView['startAction'] = $startUserAction;
            $this->dataForView['userAction'] = $nowUserAction;
            $this->dataForView['user'] = $user;
            $this->dataForView['api_token'] = $user->api_token;
            $this->dataForView['appName'] = 'pipeline-flow-view-history';
            $this->dataForView['pageTitle'] = $flow->name;
            $this->dataForView['handlerIcon'] = $handlersIcon;

            return view('h5_apps.pipeline.flow_view_history',$this->dataForView);
        }
        else{
            return '您无权使用本功能';
        }
    }
}
