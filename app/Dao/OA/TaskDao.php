<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/13
 * Time: 上午10:48
 */

namespace App\Dao\OA;


use App\Console\Commands\importer;
use App\Dao\Users\UserDao;
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
    public function getTaskMember($taskId,$userId) {
        $map = ['user_id'=>$userId, 'task_id'=>$taskId];
        return ProjectTaskMember::where($map)->first();
    }


    /**
     * 接受任务
     * @param ProjectTask $task
     * @param $userId
     * @param $taskId
     * @param $schoolId
     * @return MessageBag
     */
    public function receiveTask(ProjectTask $task, $userId, $taskId, $schoolId) {
        $messageBag = new MessageBag();
        try{
            DB::beginTransaction();
            // 任务总表状态
            if($task->status == ProjectTask::STATUS_UN_BEGIN) {
                $save = ['status'=>ProjectTask::STATUS_IN_PROGRESS];
                ProjectTask::where('id', $taskId)->update($save);
            }
            // 修改任务详情表状态
            $map = ['user_id'=>$userId, 'task_id'=>$taskId];
            $status = ['status'=>ProjectTaskMember::STATUS_IN_PROGRESS];
            ProjectTaskMember::where($map)->update($status);
            // 添加日志
            $log = ['school_id'=>$schoolId, 'user_id'=>$userId,
                'task_id'=>$taskId, 'desc'=>'接收了任务'];
            ProjectTaskLog::create($log);
            DB::commit();
            $messageBag->setMessage('接收成功');

        } catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage($e->getMessage());
        }
        return $messageBag;
    }


    /**
     * 结束任务
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
            $save = ['status'=>ProjectTaskMember::STATUS_CLOSED,
                'remark'=>$remark, 'end_time'=>Carbon::now()->toDateTimeString()];
            ProjectTaskMember::where($map)->update($save);

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


            $finish = $task->taskMembers
                ->where('status',ProjectTaskMember::STATUS_CLOSED)
                ->where('take_id','<>', $task->id);
            if(count($finish) == 0) {
                // 关闭任务
                ProjectTask::where('id',$task->id)->update(['status'=>ProjectTask::STATUS_CLOSED]);
            }

            DB::commit();
            $messageBag->setMessage('完成成功');

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
     * 删除讨论
     * @param $forumId
     * @return mixed
     */
    public function delForum($forumId) {
        return ProjectTaskDiscussion::where('id', $forumId)->delete();
    }


    /**
     * @param $taskId
     * @return mixed
     */
    public function getTaskMembersByTaskId($taskId) {
        return ProjectTaskMember::where('task_id',$taskId)->get();
    }


    /**
     * 指派任务
     * @param $userId
     * @param $taskId
     * @param $assignId
     * @param $schoolId
     * @return MessageBag
     */
    public function assignTask($userId, $taskId, $assignId, $schoolId) {
        $messageBag = new MessageBag();
        $userDao = new UserDao();
        try{
            DB::beginTransaction();
            // 添加任务指派人
            $name = [];
            foreach ($assignId as $key => $item) {
                $data = [
                    'user_id' => $item,
                    'task_id' => $taskId
                ];
                $user = $userDao->getUserById($item);
                $name[$key] = $user->name;
                ProjectTaskMember::create($data);
            }
            // 当前用户任务结束
            $map = ['task_id'=>$taskId, 'user_id'=>$userId];
            $now = Carbon::now()->toDateTimeString();
            $upd = ['status'=>ProjectTaskMember::STATUS_CLOSED,'end_time'=>$now];
            ProjectTaskMember::where($map)->update($upd);
            // 添加日志
            $name = implode(',', $name);
            $log = [
                'school_id' => $schoolId,
                'user_id' => $userId,
                'task_id' => $taskId,
                'desc' => '任务指派给了'.$name
            ];
            ProjectTaskLog::create($log);
            DB::commit();
            $messageBag->setMessage('指派成功');
        }catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('指派失败'.$e->getMessage());
        }
        return $messageBag;
    }


    /**
     * 获取任务状态未读次数
     * @param $userId
     * @return array
     */
    public function getTaskStatus($userId) {
        $notBegunMap = ['status'=>ProjectTaskMember::STATUS_UN_BEGIN,
            'user_id'=>$userId, 'not_begin'=>ProjectTaskMember::UN_READ];
        $notBegin = ProjectTaskMember::where($notBegunMap)->count();
        $underwayMap = ['status'=>ProjectTaskMember::STATUS_IN_PROGRESS,
            'user_id'=>$userId, 'underway'=>ProjectTaskMember::UN_READ];
        $underway = ProjectTaskMember::where($underwayMap)->count();
//        $finishMap = ['status'=>ProjectTaskMember::STATUS_CLOSED,
//            'user_id'=>$userId, 'underway'=>ProjectTaskMember::UN_READ];
//        $finish = ProjectTaskMember::where($finishMap)->count();
        $taskMap = ['status'=>ProjectTask::STATUS_CLOSED, 'create_user'=>$userId,
            'read_status'=>ProjectTask::UN_READ];
        $myCreate = ProjectTask::where($taskMap)->count();
        return [
            'not_begin'=>$notBegin? true: false,
            'underway'=>$underway?true:false,
//            'finish'=>$finish,
            'my_create'=>$myCreate?true:false,
        ];
    }


    /**
     * 获取讨论
     * @param $taskId
     * @param $replyUserId
     * @return mixed
     */
    public function getDiscussionByTaskIdAndReplyUserId($taskId, $replyUserId) {

        return ProjectTaskDiscussion::where('project_task_id', $taskId)
            ->whereIn('reply_user_id', [0, $replyUserId])
            ->get();
    }
}