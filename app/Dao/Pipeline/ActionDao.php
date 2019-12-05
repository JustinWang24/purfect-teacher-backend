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
use App\Utils\Pipeline\IAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActionDao
{
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
     * @return Action
     */
    public function create($data){
        DB::beginTransaction();
        try{
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
        return Action::where('user_id',$user->id??$user)
            ->join('pipeline_nodes', function ($join){
                $join->on('pipeline_actions.node_id','=','pipeline_nodes.id')
                    ->where('pipeline_nodes.prev_node','=',0);
            })
            ->with('flow')
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
            ->join('pipeline_nodes', function ($join){
                $join->on('pipeline_actions.node_id','=','pipeline_nodes.id')
                    ->where('pipeline_nodes.prev_node','>',0);
            })
            ->with('flow')
            ->get();
    }
}