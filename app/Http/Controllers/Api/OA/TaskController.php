<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/13
 * Time: 上午10:52
 */

namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\ProjectDao;
use App\Dao\OA\TaskDao;
use App\Utils\JsonBuilder;
use App\Models\OA\ProjectTaskMember;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\ProjectRequest;

class TaskController extends Controller
{
    /**
     * 创建任务
     * @param ProjectRequest $request
     * @return string
     */
    public function createTask(ProjectRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new TaskDao();
        $task_title = strip_tags($request->get('task_title'));
        $task_content = strip_tags($request->get('task_content'));
        $leader_userid = strip_tags($request->get('leader_userid'));
        $memberUserIds = explode(',',strip_tags($request->get('member_userids')));
        $end_time = $request->get('end_time');
        $projectid = intval($request->get('projectid'));
        $data = [
            'project_id'=>$projectid,
            'user_id' =>$leader_userid,
            'title' =>$task_title,
            'content' =>$task_content,
            'end_time' =>$end_time,
            'create_user'=>$user->id,
            'school_id' => $schoolId,
        ];
        $result = $dao->createTask($data, $memberUserIds);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }

    }


    /**
     * 任务详情
     * @param ProjectRequest $request
     * @return string
     */
    public function taskInfo(ProjectRequest $request)
    {
        $taskId = $request->getTaskId();
        $dao = new TaskDao();
        $task = $dao->getProjectTaskById($taskId);
        if(is_null($task)){
            return JsonBuilder::Error('没有内容');
        }

        $output['create_userid'] = $task->create_user;
        $output['create_name'] = $task->createUser->name;
        $output['task_title'] = $task->title;
        $output['task_content'] = $task->content;
        $output['create_time'] = $task->created_at;
        $output['ent_time'] = $task->ent_time;
        $output['projectid'] = $task->project_id;
        $output['project_title'] = $task->project->title ?? '';
        $output['leader_userid'] = $task->user_id;
        $output['leader_name'] = $task->user->name;
        $output['report_btn'] = 1; //结果按钮 1-显示 0-隐藏

        $members = [];
        foreach ($task->taskMembers as $key => $val) {
            $members[$key]['userid']=$val->user->id;
            $members[$key]['username']=$val->user->name;
            $members[$key]['user_pics']=$val->user->profile->avatar;
        }
        $output['member_list'] = $members;

        $logs = [];
        foreach ($task->taskLogs as $key => $item) {
            $logs[$key]['log_content'] = $item->user->name.$item->desc;
            $logs[$key]['create_time'] = $item->created_at->format('Y-m-d H:i');
        }
        $output['log_list'] = $logs;
        $forum = [];
        foreach ($task->discussions as $key => $val) {
            $forum[$key]['forumid']=$val->id;
            $forum[$key]['userid']=$val->user_id;
            $forum[$key]['username']=$val->user->name;
            $forum[$key]['user_pics']=$val->user->profile->avatar;
            $forum[$key]['forum_content']=$val->content;
            $forum[$key]['create_time']=$val->created_at->format('Y-m-d H:i');

        }
        $output['forum_list'] = $forum;
        return JsonBuilder::Success($output);

    }


    /**
     * 接受任务
     * @param ProjectRequest $request
     * @return string
     */
    public function receiveTask(ProjectRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $taskId = $request->getTaskId();
        $dao = new TaskDao();
        $task = $dao->getProjectTaskById($taskId);
        if(is_null($task)){
            return JsonBuilder::Error('没有内容');
        }

        $member = $task->taskMembers->where('user_id',$user->id)->first();
        if(is_null($member)) {
            return JsonBuilder::Error('您不属于该任务的成员');
        }
        if($member->status == ProjectTaskMember::STATUS_CLOSED) {
            return JsonBuilder::Success('任务已结束');
        }
        if($member->status == ProjectTaskMember::STATUS_IN_PROGRESS) {
            return JsonBuilder::Success('任务已接受');
        }

        $result = $dao->receiveTask($user->id, $taskId, $schoolId);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


    /**
     * 结束任务
     * @param ProjectRequest $request
     * @return string
     */
    public function finishTask(ProjectRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new TaskDao();
        $taskId = $request->getTaskId();
        $remark = $request->get('remark');
        $pics = $request->file('pics');
        $task = $dao->getProjectTaskById($taskId);
        if(is_null($task)){
            return JsonBuilder::Error('没有内容');
        }
        $member = $task->taskMembers->where('user_id',$user->id)->first();
        if(is_null($member)) {
            return JsonBuilder::Error('您不属于该任务的成员');
        }
        if($member->status == ProjectTaskMember::STATUS_CLOSED) {
            return JsonBuilder::Success('任务已结束');
        }


        $result = $dao->finishTask($user->id, $task, $member->id, $remark, $schoolId, $pics);
        $msg = $result->getMessage();
        if ($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


    /**
     * 添加讨论
     * @param ProjectRequest $request
     * @return string
     */
    public function addOaTaskForum(ProjectRequest $request) {
        $user = $request->user();
        $dao = new TaskDao();
        $taskId = $request->getTaskId();
        $task = $dao->getProjectTaskById($taskId);
        if(is_null($task)){
            return JsonBuilder::Error('没有内容');
        }
        $forum_content = $request->get('forum_content');
        $data = [
            'project_task_id' => $taskId,
            'user_id' => $user->id,
            'content' => $forum_content,
        ];
        $result = $dao->createDiscussion($data);
        if ($result) {
            return JsonBuilder::Success('添加成功');
        } else {
            return JsonBuilder::Error('添加失败');
        }

    }

}