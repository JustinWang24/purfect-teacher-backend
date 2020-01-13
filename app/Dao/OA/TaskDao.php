<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/13
 * Time: 上午10:48
 */

namespace App\Dao\OA;


use App\Models\OA\Project;
use App\Models\OA\ProjectTaskDiscussion;
use App\Models\OA\ProjectTaskPic;
use App\Utils\JsonBuilder;
use App\Models\OA\ProjectTask;
use App\Models\OA\ProjectTaskLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\OA\ProjectTaskMember;
use App\Utils\ReturnData\MessageBag;
use Ramsey\Uuid\Uuid;

class TaskDao
{

    /**
     * 添加任务
     * @param array $task
     * @param array $memberUserIds
     * @return MessageBag
     */
    public function createTask($task, $memberUserIds) {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        $re = $this->getTaskByTitleAndProjectId($task['title'], $task['project_id']);
        if(!is_null($re)) {
            $messageBag->setMessage('该任务已存在,请重新更换');
            return $messageBag;
        }

        try{
            DB::beginTransaction();
            // 创建任务
            $result = ProjectTask::create($task);
            foreach ($memberUserIds as $key => $item) {
                $user = [
                    'task_id' => $result->id,
                    'user_id' => $item
                ];
                // 创建成员
                ProjectTaskMember::create($user);
            }

            if(!empty($task['project_id'])) {
                // 修改项目状态
                Project::where('id', $task['project_id'])
                    ->update(['status'=>Project::STATUS_IN_PROGRESS]);
            }

            // 创建日志
            $log = ['task_id'=>$result->id, 'school_id'=>$task['school_id'],
                'user_id'=>$task['user_id'], 'desc'=>'创建任务'];
            ProjectTaskLog::create($log);

            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData(['id'=>$result->id]);
        }catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setMessage($msg);
        }
        return $messageBag;
    }


    /**
     * 通过title和projectId查询任务
     * @param $title
     * @param $projectId
     * @return mixed
     */
    public function getTaskByTitleAndProjectId($title, $projectId) {
        $map = ['title'=>$title, 'project_id'=>$projectId];
        return ProjectTask::where($map)->first();
    }


    /**
     * 查看详情
     * @param $taskId
     * @return ProjectTask
     */
    public function getProjectTaskById($taskId){
        return ProjectTask::find($taskId);
    }


    /**
     * 查看任务状态
     * @param $taskId
     * @param $userId
     * @return mixed
     */
    public function getTaskStatus($taskId,$userId) {
        $map = ['user_id'=>$userId, 'task_id'=>$taskId];
        return ProjectTaskMember::where($map)->first();
    }


    /**
     * 接受任务
     * @param $userId
     * @param $taskId
     * @param $schoolId
     * @return MessageBag
     */
    public function receiveTask($userId, $taskId, $schoolId) {
        $messageBag = new MessageBag();
        try{
            DB::beginTransaction();
            // 修改接受任务
            $map = ['user_id'=>$userId, 'task_id'=>$taskId];
            $status = ['status'=>ProjectTaskMember::STATUS_IN_PROGRESS];
            ProjectTaskMember::where($map)->update($status);
            // 添加日志
            $log = ['school_id'=>$schoolId, 'user_id'=>$userId,
                'task_id'=>$taskId, 'desc'=>'接受了任务'];
            ProjectTaskLog::create($log);
            DB::commit();
            $messageBag->setMessage('接受成功');

        } catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage($e->getMessage());
        }
        return $messageBag;
    }


    /**
     * @param $userId
     * @param $task
     * @param $taskMemberId
     * @param $remark
     * @param $schoolId
     * @param $pics
     * @return MessageBag
     */
    public function finishTask($userId, $task,$taskMemberId, $remark, $schoolId, $pics) {
        $messageBag = new MessageBag();
        try{
            DB::beginTransaction();
            // 修改完成任务
            $map = ['user_id'=>$userId, 'task_id'=>$task->id];
            $status = ['status'=>ProjectTaskMember::STATUS_CLOSED,
                'remark'=>$remark, 'end_time'=>Carbon::now()->toDateTimeString()];
            ProjectTaskMember::where($map)->update($status);

            // 添加日志
            $log = ['school_id'=>$schoolId, 'user_id'=>$userId,
                'task_id'=>$task->id, 'desc'=>'完成了任务'];
            ProjectTaskLog::create($log);

            // 添加附件
            $path = ProjectTaskPic::DEFAULT_UPLOAD_PATH_PREFIX.$userId; // 上传路径

            foreach ($pics as $key => $item) {
                $uuid = Uuid::uuid4()->toString();
                $url = $item->storeAs($path, $uuid. '.' .$item->getClientOriginalExtension()); // 上传并返回路径
                $data = [
                    'url' => ProjectTaskPic::ConvertUploadPathToUrl($url),
                    'task_id' => $task->id,
                    'task_member_id' => $taskMemberId,
                ];
                ProjectTaskPic::create($data);
            }

            // 判断是否都结束
            $count = $task->taskMembers->count();
            $finish = $task->taskMembers->where('status',ProjectTaskMember::STATUS_CLOSED)->count();
            if($count == $finish) {
                // 关闭任务
                ProjectTask::where('id',$task->id)->update(['status'=>ProjectTask::STATUS_CLOSED]);
            }

            DB::commit();
            $messageBag->setMessage('结束成功');

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->getCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage($msg);
        }
        return $messageBag;

    }


    /**
     * 创建任务评论
     * @param $data
     * @return mixed
     */
    public function createDiscussion($data) {
        return ProjectTaskDiscussion::create($data);
    }


    /**
     * @param $taskId
     * @return mixed
     */
    public function getTaskMembersByTaskId($taskId) {
        return ProjectTaskMember::where('task_id',$taskId)->get();
    }
}