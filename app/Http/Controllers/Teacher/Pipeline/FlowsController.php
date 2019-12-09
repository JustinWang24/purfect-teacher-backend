<?php
/**
 * 教师处理自己流程的控制器
 */

namespace App\Http\Controllers\Teacher\Pipeline;
use App\BusinessLogic\Pipeline\Flow\FlowLogicFactory;
use App\Dao\Pipeline\ActionDao;
use App\Dao\Pipeline\FlowDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;
use App\Utils\Pipeline\FlowEngineFactory;
use App\Utils\Pipeline\IAction;

class FlowsController extends Controller
{
    /**
     * 打开一个流程的预备操作
     * @param FlowRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function open(FlowRequest $request){
        $dao = new FlowDao();
        $flow = $dao->getById($request->get('flow'));
        $this->dataForView['pageTitle'] = '开始流程: ' . $flow->name;
        $this->dataForView['flow'] = $flow;

        $logic = FlowLogicFactory::GetInstance($request->user());
        $msgBag = $logic->open($flow);

        if($msgBag->isSuccess()){
            $this->dataForView = array_merge($this->dataForView, $msgBag->getData());
            return view('pipeline.flows.open',$this->dataForView);
        }
    }

    /**
     * 查看某个申请的详情
     * @param FlowRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_history(FlowRequest $request){
        $this->dataForView['pageTitle'] = '查看申请';

        $userFlowId = $request->getUserFlowId();
        $this->dataForView['actionId'] = $request->getActionId();
        $dao = new ActionDao();
        if($userFlowId){
            $action = $dao->getLastActionByUserFlow($userFlowId);
            $this->dataForView['showActionEditForm'] = false;
        }else{
            $action = $dao->getByActionIdAndUserId($request->getActionId(), $request->user()->id);
            $this->dataForView['showActionEditForm'] =
                $action->result === IAction::RESULT_PENDING && $action->user_id === $request->user()->id;
        }

        $this->dataForView['action'] = $action;

        $this->dataForView['userFlowId'] = $request->getUserFlowId();
        return view('pipeline.flows.view_history',$this->dataForView);
    }

    /**
     * 教师开始一个流程, 这个时候, 如果执行成功, 流程就算正式开始了
     * @param FlowRequest $request
     */
    public function start(FlowRequest $request){
        $actionData = $request->getStartFlowData();
        $logic = FlowLogicFactory::GetInstance($request->user());
    }
}