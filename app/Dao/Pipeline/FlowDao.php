<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:04 AM
 */

namespace App\Dao\Pipeline;

use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\UserFlow;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class FlowDao
{
    /**
     * 获取分类的流程集合
     * @param $schoolId
     * @param array $types
     * @return array
     */
    public function getGroupedFlows($schoolId, $types = []){
        $flows = Flow::select(['id','name','icon','type'])
            ->where('school_id',$schoolId)
            ->orderBy('type','asc')->get();
        $data = [];
        if(empty($types)){
            $types = array_keys(Flow::Types());
        }

        foreach ($types as $key){
            $data[$key] = [];
        }

        foreach ($flows as $flow) {
            if(in_array($flow->type, $types)){
                $data[$flow->type][] = $flow;
            }
        }

        $groups = [];

        foreach ($data as $key=>$items) {
            $groups[] = [
                'name'=>Flow::Types()[$key],
                'key'=>$key,
                'flows'=>$items
            ];
        }

        return $groups;
    }

    /**
     * 开始一个流程
     * @param Flow|int $flow
     * @param User|int $user
     * @return IMessageBag
     */
    public function start($flow, $user){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        $flowId = $flow;
        if(is_object($flow)){
            $flowId = $flow->id;
        }
        // 获取第一个 node
        $nodeDao = new NodeDao();
        $headNode = $nodeDao->getHeadNodeByFlow($flow);
        if($headNode){
            $actionData = [
                'flow_id'=>$flowId,
                'user_id'=>$user->id??$user,
                'node_id'=>$headNode->id,
                'result'=>IAction::RESULT_PENDING,
            ];

            DB::beginTransaction();

            try{
                $userFlow = UserFlow::create(
                    ['flow_id' => $flowId, 'user_id' => $user->id??$user]
                );
                $actionDao = new ActionDao();
                $action = $actionDao->create($actionData, $userFlow);
                $bag->setData($action);
                $bag->setCode(JsonBuilder::CODE_SUCCESS);
                DB::commit();
            }
            catch (\Exception $exception){
                DB::rollBack();
                $bag->setMessage($exception->getMessage());
            }
            return $bag;
        }

        $bag->setMessage('找不到指定的流程的第一步');
        return $bag;
    }

    /**
     * @param $id
     * @return Flow
     */
    public function getById($id){
        return Flow::find($id);
    }

    /**
     * 创建流程, 那么应该同时创建第一步, "发起" node. 也就是表示, 任意流程, 创建时会默认创建头部
     * @param $data
     * @param string $headNodeDescription
     * @param array $nodeAndHandlersDescriptor: 该流程可以由谁来发起, 如果为空数组, 表示可以由任何人发起.
     * @return IMessageBag
     */
    public function create($data, $headNodeDescription = '', $nodeAndHandlersDescriptor = []){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();

        try{
            $flow = Flow::create($data);
            $nodeDao = new NodeDao();
            // 创建流程后, 默认必须创建一个"发起"的步骤作为第一步
            $headNode = $nodeDao->insert([
                'name'=>'发起'.$flow->name.'流程',
                'description'=>$headNodeDescription,
                'attachments'=>$nodeAndHandlersDescriptor['attachments']
            ], $flow);

            // 创建头部流程的 handlers
            $handlerDao = new HandlerDao();
            $handlerDao->create($headNode, $nodeAndHandlersDescriptor);

            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($flow);
            return $bag;
        }
        catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
            return $bag;
        }
    }

    /**
     * @param $flowId
     * @return mixed
     */
    public function delete($flowId){
        return Flow::where('id',$flowId)->delete();
    }
}