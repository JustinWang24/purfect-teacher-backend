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
use App\User;
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
        $this->dataForView['pageTitle'] = '办事大厅';

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

    public function in_progress(Request $request){
        /**
         * @var User $user
         */
        $user = $request->user('api');

        if($user){
            $logic = FlowLogicFactory::GetInstance($user);
            $flows = $logic->startedByMe();
            $this->dataForView['user'] = $user;
            $this->dataForView['flows'] = $flows;
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
        /**
         * @var User $user
         */
        $user = $request->user('api');
        $actionId = $request->get('action_id',null);
        $userFlowId = $request->get('user_flow_id', null);

        if($user && $userFlowId){
            $dao = new ActionDao();
            if($userFlowId){
                $action = $dao->getLastActionByUserFlow($userFlowId);
                $this->dataForView['showActionEditForm'] = false;
            }else{
                $action = $dao->getByActionIdAndUserId($actionId, $user->id);
                $this->dataForView['showActionEditForm'] =
                    $action->result === IAction::RESULT_PENDING && $action->user_id === $actionId->id;
            }

            $this->dataForView['node'] = $action->node;
            $this->dataForView['action'] = $action;
            $this->dataForView['userFlow'] = $action->userFlow;
            $this->dataForView['actionId'] = $actionId;
            $this->dataForView['userFlowId'] = $userFlowId;
            $this->dataForView['user'] = $user;
            $this->dataForView['api_token'] = $user->api_token;
            $this->dataForView['appName'] = 'pipeline-flow-view-history';
            $this->dataForView['pageTitle'] = $action->getFlow()->getName();

            return view('h5_apps.pipeline.flow_view_history',$this->dataForView);
        }
        else{
            return '您无权使用本功能';
        }
    }
}
