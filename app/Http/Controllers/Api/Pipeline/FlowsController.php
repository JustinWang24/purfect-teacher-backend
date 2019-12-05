<?php
/**
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:40 PM
 */
namespace App\Http\Controllers\Api\Pipeline;
use App\BusinessLogic\Pipeline\Flow\FlowLogicFactory;
use App\Dao\Pipeline\FlowDao;
use App\Events\Pipeline\Flow\FlowStarted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;
use App\Models\Pipeline\Flow\Action;
use App\Models\Pipeline\Flow\Node;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\FlowEngineFactory;

class FlowsController extends Controller
{
    /**
     * @param FlowRequest $request
     * @return string
     */
    public function my(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        return JsonBuilder::Success(['flows'=>$logic->getMyFlows()]);
    }

    /**
     * @param FlowRequest $request
     * @return string
     */
    public function started_by_me(FlowRequest $request){
        $logic = FlowLogicFactory::GetInstance($request->user());
        return JsonBuilder::Success(['actions'=>$logic->startedByMe()]);
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
            FlowEngineFactory::GetInstance($flow, $request->user()),
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
}