<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 3:50 PM
 */

namespace App\Http\Controllers\Operator\Pipeline;
use App\Dao\Pipeline\FlowDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\NodeHandlersDescriptor;

class FlowsController extends Controller
{
    /**
     * @param FlowRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager(FlowRequest $request){
        $this->dataForView['pageTitle'] = '工作流程管理';
        $dao = new FlowDao();
        $this->dataForView['groupedFlows'] = $dao->getGroupedFlows($request->session()->get('school.id'));
        $this->dataForView['lastNewFlow'] = $request->getLastNewFlow(); // 上次可能刚创建了新流程

        return view('school_manager.pipeline.flow.manager', $this->dataForView);
    }

    /**
     * 保存流程
     * @param FlowRequest $request
     * @return string
     */
    public function save_flow(FlowRequest $request){
        $flow = $request->getFlowFormData();

        if(empty($flow['id'])){
            // 创建新流程
            $node = $request->getNewFlowFirstNode();
            $description = $node['description'];

            $dao = new FlowDao();
            $result = $dao->create($flow, $description, $node);
            return $result->isSuccess() ?
                JsonBuilder::Success(['id'=>$result->getData()->id]) :
                JsonBuilder::Error($result->getMessage());
        }
        else{
            // 更新
        }
    }

    /**
     * 加载某个流程的所有步骤
     * @param FlowRequest $request
     * @return string
     */
    public function load_nodes(FlowRequest $request){
        $dao = new FlowDao();
        $flow = $dao->getById($request->get('flow_id'));
        if($flow){
            $nodes = $flow->getSimpleLinkedNodes();
            return JsonBuilder::Success(['flow'=>$flow,'nodes'=>$nodes]);
        }
        return JsonBuilder::Error();
    }
}