<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 3:50 PM
 */

namespace App\Http\Controllers\Operator\Pipeline;
use App\Dao\Pipeline\FlowDao;
use App\Dao\Pipeline\HandlerDao;
use App\Dao\Pipeline\NodeDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;
use App\Models\Pipeline\Flow\NodeAttachment;
use App\Utils\JsonBuilder;

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
    }

    /**
     * 保存流程中的步骤节点
     * @param FlowRequest $request
     * @return string
     */
    public function save_node(FlowRequest $request){
        $nodeData = $request->getNewFlowFirstNode();
        $flowId = $request->get('flow_id');
        $prevNodeId = $request->get('prev_node');
        $flowDao = new FlowDao();
        $flow = $flowDao->getById($flowId);
        $nodeDao = new NodeDao();
        $prevNode = $nodeDao->getById($prevNodeId);
        $result = $nodeDao->insert($nodeData, $flow, $prevNode);
        if(is_object($result)){
            $handlerDao = new HandlerDao();
            $handlerDao->create($result, $nodeData);
            return JsonBuilder::Success(['nodes'=>$flow->getSimpleLinkedNodes()]);
        }
        else{
            return JsonBuilder::Error($result);
        }
    }

    /**
     * 更新节点的操作
     * @param FlowRequest $request
     * @return string
     */
    public function update_node(FlowRequest $request){
        $nodeData = $request->getNewFlowFirstNode();
        $flowId = $request->get('flow_id');
        $prevNodeId = $request->get('prev_node');
        $organizationsLeftOver = $request->get('prev_orgs'); // 遗留的组织

        $flowDao = new FlowDao();
        $flow = $flowDao->getById($flowId);

        $nodeDao = new NodeDao();
        $prevNode = $nodeDao->getById($prevNodeId);

        $result = $nodeDao->update($nodeData, $prevNode, $flow, $organizationsLeftOver);

        if($result === true){
            return JsonBuilder::Success(['nodes'=>$flow->getSimpleLinkedNodes()]);
        }
        else{
            return JsonBuilder::Error($result);
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

    /**
     * 删除流程中的节点
     * @param FlowRequest $request
     * @return string
     */
    public function delete_node(FlowRequest $request){
        $schoolId = $request->get('school_id');
        if(intval($schoolId) !== $request->session()->get('school.id')){
            return JsonBuilder::Error('您无权进行此操作');
        }

        $nodeId = $request->get('node_id');
        $dao = new NodeDao();
        $result = $dao->delete($nodeId);
        if($result === true){
            return JsonBuilder::Success();
        }
        else{
            return JsonBuilder::Error('系统繁忙, 请稍候再试');
        }
    }

    /**
     * 删除步骤所关联的附件
     * @param FlowRequest $request
     * @return string
     */
    public function delete_node_attachment(FlowRequest $request){
        if(NodeAttachment::where('id',$request->get('attachment_id'))->delete()){
            return JsonBuilder::Success();
        }
        else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 删除流程
     * @param FlowRequest $request
     * @return string
     */
    public function delete_flow(FlowRequest $request)
    {
        $id = $request->get('flow_id');
        $dao = new FlowDao();
        if($dao->delete($id)){
            return JsonBuilder::Success();
        }
        else{
            return JsonBuilder::Error();
        }
    }
}