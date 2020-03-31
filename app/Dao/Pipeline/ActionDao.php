<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:08 AM
 */

namespace App\Dao\Pipeline;
use App\Dao\NetworkDisk\MediaDao;
use App\Events\Pipeline\Flow\FlowStarted;
use App\Models\OA\NewMeeting;
use App\Models\Pipeline\Flow\Action;
use App\Models\Pipeline\Flow\ActionAttachment;
use App\Models\Pipeline\Flow\ActionOption;
use App\Models\Pipeline\Flow\Copys;
use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\UserFlow;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\IUserFlow;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function foo\func;

class ActionDao
{
    public function createMeetingFlow(User $user, $meetid){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        //获取meeting对应的flow
        $dao = new FlowDao();
        $result =  $dao->getListByBusiness($user->getSchoolId(), IFlow::BUSINESS_TYPE_MEETING);
        $retFlow = [];
        foreach ($result as $flow) {
            if ($dao->checkPermissionByuser($flow, $user, 0)) {
                $retFlow = $flow;
                break;
            }
        }
        if (empty($retFlow)) {
            $bag->setMessage('用户权限不足');
            return $bag;
        }
        $startNode = $retFlow->getHeadNode();

        //获取meeting
        $meet = NewMeeting::find($meetid);
        //获取表单
        $options = [];
        foreach ($startNode->options as $option) {
            if ($option->title == '会议id') {
                $options[] = [
                    'id' => $option->id,
                    'value' => $meet->id
                ];
            }
            if ($option->title == '会议主题') {
                $options[] = [
                    'id' => $option->id,
                    'value' => $meet->meet_title
                ];
            }
            if ($option->title == '参会人数') {
                $users = $meet->meetUsers->pluck('user_id')->toArray();
                array_push($users, $meet['approve_userid']);
                $users = array_unique($users);

                $options[] = [
                    'id' => $option->id,
                    'value' => count($users)
                ];
            }
            if ($option->title == '会议室名称') {
                $options[] = [
                    'id' => $option->id,
                    'value' => $meet->room->name
                ];
            }
            if ($option->title == '开始时间') {
                $options[] = [
                    'id' => $option->id,
                    'value' => $meet->meet_start . ' ~ ' . $meet->meet_end
                ];
            }
        }
        $actionData = [
            'flow_id' => $retFlow->id,
            'content' => '',
            'attachments' => [],
            'urgent' => false,
            'options' => $options
        ];

        $actionData['node_id'] = $startNode->id;
        $actionData['user_id'] = $user->id;
        $actionData['result'] = IAction::RESULT_PASS; // 启动工作没有审核, 直接就是 pass 的状态
        //@TODO 不启用加急功能
        $actionData['urgent'] = 0;
        DB::beginTransaction();
        try{
            // 先为当前的用户创建一个用户流程
            $userFlow = UserFlow::create([
                'flow_id'=>$retFlow->id,
                'user_id'=>$user->id,
                'user_name'=>$user->getName(),
                'done'=>IUserFlow::IN_PROGRESS,
            ]);

            // 用户流程创建之后, 创建该流程的第一个 action
            $action = $this->create($actionData, $userFlow);

            // 第一个 action 创建成功后, 找到流程的第二步, 然后针对第二步所依赖的审批人(handlers), 创建需要执行的 actions
            // 生成下一步需要的操作
            $handler = $startNode->getHandler();
            if($handler){
                // 根据当前提交 action 的用户和流程, 创建所有需要的下一步流程
                $handler->handle($user, $action, $startNode->getNext());
            }

            // 发布流程启动成功事件
            event(new FlowStarted($user,$action, $retFlow, $startNode));

            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            DB::commit();
        }catch (\Exception $exception){
            $bag->setMessage($exception->getMessage().', line='.$exception->getLine());
            DB::rollBack();
        }

        return $bag;
    }
    /**
     * @param User $user
     * @param $userFlowId
     * @return IMessageBag
     */
    public function cancelUserFlow($user, $userFlowId){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        // 定位指定的 action
        /**
         * @var UserFlow $userFlow
         */
        if($user->isSchoolAdminOrAbove()){
            $userFlow = UserFlow::find($userFlowId);
        }
        else{
            $userFlow = UserFlow::where('id',$userFlowId)->where('user_id',$user->id)->first();
        }

        if($userFlow){
            //获取发起人节点
            $firstNode = $userFlow->flow->getHeadNode();
            // 找到了 action, 那么就删除此 action 已经相关的操作
            DB::beginTransaction();
            try{
                $actions = $userFlow->actions;

                foreach ($actions as $action) {
                    //保留发起人的节点
                    if ($action->node_id == $firstNode->id) {
                        continue;
                    }
                    $this->delete($action->id);
                }

                $userFlow->done = IUserFlow::REVOKE;
                $userFlow->save();

                DB::commit();
                $bag->setCode(JsonBuilder::CODE_SUCCESS);
            }
            catch (\Exception $exception){
                $bag->setMessage($exception->getMessage());
                DB::rollBack();
            }
        }
        else{
            $bag->setMessage('您无权进行此操作');
        }
        return $bag;
    }

    /**
     * @param $result
     * @param $flow
     * @param null $user
     * @return Action
     */
    public function getByFlowAndResult($result, $flow, $user){
        $where = [
            ['result','=',$result]
        ];
        if(is_object($flow)){
            $flowId = $flow->id;
        }
        else{
            $flowId = $flow;
        }
        $where[] = ['flow_id','=',$flowId];

        if(is_object($user)){
            $userId = $user->id;
        }
        else{
            $userId = $user;
        }
        $where[] = ['user_id','=',$userId];
        return Action::where($where)
            ->with('options')
            ->with('attachments')
            ->first();
    }

    /**
     * @param $data
     * @param IUserFlow|int $userFlow: 用户流程接口的对象或者用户流程接口 ID
     * @return Action
     */
    public function create($data, $userFlow){
        DB::beginTransaction();
        try{
            // 为了可以让流程的动作形成关联关系, 必须生成一个标识当前流程的 transaction id.
            $data['transaction_id'] = $userFlow->id ?? $userFlow;

            $action = Action::create($data);
            if(isset($data['attachments'])){
                foreach ($data['attachments'] as $attachment) {
                    if(is_array($attachment)){
                        $attachment['action_id'] = $action->id;
                        ActionAttachment::create($attachment);
                    }
                    elseif (is_string($attachment) || is_int($attachment)){
                        // 可能是手机 APP 调用传来的
                        $media = (new MediaDao())->getMediaById($attachment);
                        if($media){
                            ActionAttachment::create(
                                [
                                    'action_id'=>$action->id,
                                    'media_id'=>$attachment,
                                    'url'=>$media->url,
                                    'file_name'=>$media->file_name,
                                ]
                            );
                        }
                    }
                }
            }
            // 保存申请人提交的必选项
            if(isset($data['options'])){
                foreach ($data['options'] as $option) {
                    $actionOptionData = [
                        'action_id' => $action->id,
                        'option_id' => $option['id'],
                        'value' => is_string($option['value']) ? $option['value'] : json_encode($option['value']),
                    ];
                    ActionOption::create($actionOptionData);
                }
            }

            DB::commit();
            return $action;
        }
        catch (\Exception $exception){
            DB::rollBack();
            Log::alert('创建工作流步骤的 action 失败',['msg'=>$exception->getMessage(),'data'=>$data]);
            return null;
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public function delete($id){
        DB::beginTransaction();
        try{
            Action::where('id',$id)->delete();
            ActionAttachment::where('action_id',$id)->delete();
            ActionOption::where('action_id',$id)->delete();
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    /**
     * 获取给定用户的所有发起的流程
     * @param $user
     * @return Collection
     */
    public function getFlowsWhichStartBy($user, $position = 0, $keyword = '', $size = ConfigurationTool::DEFAULT_PAGE_SIZE){
        $return = UserFlow::where('user_id',$user->id??$user);
        if ($position) {
            $typeArr = array_keys(Flow::getTypesByPosition($position));
            if ($position == 1) {
                $typeArr = array_merge($typeArr, array_keys(Flow::getTypesByPosition(3)));
            }
            $flowIdArr = Flow::whereIn('type', $typeArr)->pluck('id')->toArray();
            $return->whereIn('flow_id', $flowIdArr);
        }
        if ($keyword) {
            $flowIdArr = Flow::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $return->where(function ($query) use ($flowIdArr) {
                $query->whereIn('flow_id', $flowIdArr);
            });
        }
        $return->with('flow')->orderBy('id','desc');
        return $return->paginate($size);
    }

    /**
     * 获取抄送我的流程
     * @param $user
     * @return mixed
     */
    public function getFlowsWhichCopyTo($user, $position = 0, $keyword = '', $size = ConfigurationTool::DEFAULT_PAGE_SIZE){
        $return = UserFlow::whereHas('copys', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
        if ($position) {
            $typeArr = array_keys(Flow::getTypesByPosition($position));
            if ($position == 1) {
                $typeArr = array_merge($typeArr, array_keys(Flow::getTypesByPosition(3)));
            }
            $flowIdArr = Flow::whereIn('type', $typeArr)->pluck('id')->toArray();
            $return->whereIn('flow_id', $flowIdArr);
        }
        if ($keyword) {
            $flowIdArr = Flow::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $userIdArr = User::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $return->where(function ($query) use ($flowIdArr, $userIdArr) {
                $query->whereIn('flow_id', $flowIdArr)->orWhereIn('user_id', $userIdArr);
            });
        }
        $return->with('flow')->orderBy('id','desc');
        return $return->paginate($size);
    }

    /**
     * 我审批的
     * @param $user
     * @return mixed
     */
    public function getFlowsWhichMyProcessed($user, $position = 0, $keyword = '', $size = ConfigurationTool::DEFAULT_PAGE_SIZE){
        $return = UserFlow::whereHas('actions', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('is_start', 0)->where('result', '>', IAction::RESULT_PENDING);
        });
        if ($position) {
            $typeArr = array_keys(Flow::getTypesByPosition($position));
            if ($position == 1) {
                $typeArr = array_merge($typeArr, array_keys(Flow::getTypesByPosition(3)));
            }
            $flowIdArr = Flow::whereIn('type', $typeArr)->pluck('id')->toArray();
            $return->whereIn('flow_id', $flowIdArr);
        }
        if ($keyword) {
            $flowIdArr = Flow::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $userIdArr = User::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $return->where(function ($query) use ($flowIdArr, $userIdArr) {
                $query->whereIn('flow_id', $flowIdArr)->orWhereIn('user_id', $userIdArr);
            });
        }
        $return->with('flow')->orderBy('id','desc');
        return $return->paginate($size);
    }

    /**
     * 获取给定用户的所有等待审核的流程
     * @param $user
     * @return Collection
     */
    public function getFlowsWaitingFor($user, $position = 0, $keyword = '', $size = ConfigurationTool::DEFAULT_PAGE_SIZE){
        $return = Action::where('user_id',$user->id??$user)
            ->where('result','=',IAction::RESULT_PENDING);
        if ($position) {
            $typeArr = array_keys(Flow::getTypesByPosition($position));
            if ($position == 1) {
                $typeArr = array_merge($typeArr, array_keys(Flow::getTypesByPosition(3)));
            }
            $flowIdArr = Flow::whereIn('type', $typeArr)->pluck('id')->toArray();
            $return->whereIn('flow_id', $flowIdArr);
        }
        if ($keyword) {
            $flowIdArr = Flow::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $userIdArr = User::where('name', 'like', '%'.$keyword.'%')->pluck('id')->toArray();
            $userFlowIdArr = UserFlow::whereIn('user_id', $userIdArr)->pluck('id')->toArray();
            $return->where(function ($query) use ($flowIdArr, $userFlowIdArr) {
                $query->whereIn('flow_id', $flowIdArr)->orWhereIn('transaction_id', $userFlowIdArr);
            });
        }

        $return->with('flow')->with('userFlow')->orderBy('id','desc');
        return $return->paginate($size);
    }

    /**
     * 根据 user flow 的 id 获取历史记录
     * @param $userFlowId
     * @param $actionsOnly
     * @return Collection
     */
    public function getHistoryByUserFlow($userFlowId, $actionsOnly = false){
        if($actionsOnly){
            return Action::where('transaction_id',$userFlowId)
                ->with('options')
                ->orderBy('id','desc')
                ->get();
        }
        return Action::where('transaction_id',$userFlowId)
            ->with('node')
            ->with('options')
            ->orderBy('id','desc')
            ->get();
    }

    /**
     * 根据 user flow 的 id 获取最后一个动作
     * @param $userFlowId
     * @return Action
     */
    public function getLastActionByUserFlow($userFlowId){
        return Action::where('transaction_id',$userFlowId)
            ->with('node')
            ->with('options')
            ->with('attachments')
            ->orderBy('id','desc')
            ->first();
    }

    /**
     * 根据 user flow 的 id 获取第一个动作, 就是整个流程发起的动作
     * @param $userFlowId
     * @return Action
     */
    public function getFirstActionByUserFlow($userFlowId){
        return Action::where('transaction_id',$userFlowId)
            ->with('node')
            ->with('options')
            ->with('attachments')
            ->orderBy('id','asc')
            ->first();
    }

    public function getActionByUserFlowAndUserId($userFlowId, $userId){
        return Action::where('transaction_id',$userFlowId)
            ->where('user_id', $userId)
            ->with('node')
            ->with('options')
            ->with('attachments')
            ->orderBy('id','desc')
            ->first();
    }

    /**
     * @param $actionId
     * @param $userId
     * @return Action
     */
    public function getByActionIdAndUserId($actionId, $userId){
        return Action::where('id',$actionId)
            ->where('user_id',$userId)
            ->with('options')
            ->with('attachments')
            ->first();
    }

    /**
     * @param $actionId
     * @return Action
     */
    public function getByActionId($actionId){
        return Action::where('id',$actionId)
            ->with('options')
            ->with('attachments')
            ->first();
    }

    public function getCountWaitProcessUsers($nodeId, $userFlowId){
        return Action::where([
            'node_id' => $nodeId,
            'result' => IAction::RESULT_PENDING,
            'transaction_id' => $userFlowId
        ])->count();
    }
}
