<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:08 AM
 */

namespace App\Dao\Pipeline;
use App\Dao\NetworkDisk\MediaDao;
use App\Models\Pipeline\Flow\Action;
use App\Models\Pipeline\Flow\ActionAttachment;
use App\Models\Pipeline\Flow\UserFlow;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IUserFlow;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActionDao
{
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
            // 找到了 action, 那么就删除此 action 已经相关的操作
            DB::beginTransaction();
            try{
                $actions = $userFlow->actions;

                foreach ($actions as $action) {
                    $this->delete($action->id);
                }
                $userFlow->delete();

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
        return Action::where($where)->first();
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
    public function getFlowsWhichStartBy($user){
        return UserFlow::where('user_id',$user->id??$user)
            ->with('flow')
            ->orderBy('id','asc')
            ->get();
    }

    /**
     * 获取给定用户的所有等待审核的流程
     * @param $user
     * @return Collection
     */
    public function getFlowsWaitingFor($user){
        return Action::where('user_id',$user->id??$user)
            ->where('result','=',IAction::RESULT_PENDING)
            ->with('flow')
            ->with('userFlow')
            ->orderBy('id','asc')
            ->get();
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
                ->orderBy('id','asc')
                ->get();
        }
        return Action::where('transaction_id',$userFlowId)
            ->with('node')
            ->orderBy('id','asc')
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
            ->orderBy('id','asc')
            ->first();
    }

    /**
     * @param $actionId
     * @param $userId
     * @return Action
     */
    public function getByActionIdAndUserId($actionId, $userId){
        return Action::where('id',$actionId)->where('user_id',$userId)->first();
    }

    /**
     * @param $actionId
     * @return Action
     */
    public function getByActionId($actionId){
        return Action::find($actionId);
    }
}