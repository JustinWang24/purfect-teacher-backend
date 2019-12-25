<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\Teachers\TeacherProfileDao;
use App\Models\OA\Project;
use App\Models\OA\ProjectTask;
use App\Utils\JsonBuilder;
use App\Dao\OA\ProjectDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\ProjectRequest;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{

    /**
     * 创建项目
     * @param ProjectRequest $request
     * @return string
     */
    public function createProject(ProjectRequest $request) {
        $user = $request->user();
        $data['school_id'] = $user->getSchoolId();
        $data['title'] = strip_tags($request->get('project_title'));
        $data['content'] = strip_tags($request->get('project_content'));
        $data['user_id'] = intval($request->get('leader_userid'));
        $data['is_open'] = intval($request->get('is_open'));
        $data['create_user'] = $user->id;
        $member = explode(',',$request->get('member_userids'));
        $dao = new ProjectDao();
        $result = $dao->createProject($data, $member);
        if($result->isSuccess()){
            $data = $result->getData();
            return JsonBuilder::Success($data);
        } else {
            $msg = $result->getMessage();
            return JsonBuilder::Error($msg);
        }
    }

    /**
     * 创建任务
     * @param ProjectRequest $request
     * @return string
     */
    public function createTask(ProjectRequest $request) {
        $user = $request->user();
        $data['school_id'] = $user->getSchoolId();
        $dao = new ProjectDao();
        $task_title = strip_tags($request->get('task_title'));
        $task_content = strip_tags($request->get('task_content'));
        $leader_userid = strip_tags($request->get('leader_userid'));
        $member_userids = explode(',',strip_tags($request->get('member_userids')));
        $end_time = strtotime($request->get('end_time'));
        $projectid = intval($request->get('projectid'));
        $data = [
            'project_id'=>$projectid,
            'user_id' =>$leader_userid,
            'title' =>$task_title,
            'content' =>$task_content,
            'end_time' =>$end_time,
        ];
        $result = $dao->createTask($data);
        if($result->isSuccess()) {
            $dao->updateMembers($projectid, $member_userids);
            return JsonBuilder::Success($result->getData());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }

    }


    /**
     * 创建任务评论
     * @param ProjectRequest $request
     * @return string
     */
    public function createDiscussion(ProjectRequest $request) {
        $data = $request->getDiscussion();
        $dao = new ProjectDao();
        $result = $dao->createDiscussion($data);
        if($result) {
            return JsonBuilder::Success(['id'=>$result->id]);
        } else {
            return JsonBuilder::Error('创建失败');
        }
    }


    /**
     * 查看项目列表
     * @param ProjectRequest $request
     * @return string
     */
    public function projectList(ProjectRequest $request) {
        $userId = $request->user()->id;
        $schoolId = $request->user()->getSchoolId();
        $dao = new ProjectDao();
        $list = $dao->getProjectsPaginateBySchool($schoolId);
        return JsonBuilder::Success(outputTranslate($list->items(),Project::MAP_ARR));
    }


    /**
     * 查看项目下的任务列表
     * @param ProjectRequest $request
     * @return string
     */
/*    public function taskList(ProjectRequest $request) {
        $projectId = $request->getProjectId();
        if(is_null($projectId)) {
            return JsonBuilder::Error('项目ID不能为空');
        }
        $dao = new ProjectDao();
        $list = $dao->getTasksPaginateByProject($projectId);
        foreach ($list as $key => $val) {
            $val->user_field = ['name'];
            $val->user;
        }
        $data = pageReturn($list);
        return JsonBuilder::Success($data);
    }*/


    /**
     * 根据任务查看评论
     * @param ProjectRequest $request
     * @return string
     */
    public function discussionList(ProjectRequest $request) {
        $taskId = $request->getTaskId();
        if(is_null($taskId)) {
            return JsonBuilder::Error('任务ID不能为空');
        }
        $dao = new ProjectDao();
        $result = $dao->getDiscussionByTaskId($taskId);
        foreach ($result as $key => $val) {
            $val->user_field = ['name'];
            $val->user;
        }
        $data = ['discussion'=>$result];
        return JsonBuilder::Success($data);
    }


    /**
     * 项目详情
     * @param ProjectRequest $request
     * @return string
     */
    public function projectInfo(ProjectRequest $request) {
        $projectId = $request->get('projectid');
        if(is_null($projectId)) {
            return JsonBuilder::Error('项目ID不能为空');
        }
        $dao = new ProjectDao();
        $info = $dao->getProjectById($projectId);
        if (!$info){
            return JsonBuilder::Error('没有这个项目');
        }
        $output = outputTranslate($info->toArray(),Project::MAP_ARR);
        $output['leader_name'] = $info->user->name;
        $output['create_userid'] = $info->create_user;
        $output['create_name'] = '';
        $output['is_open'] = $info->is_open;
        $members = $info->members;
        $tasks = $info->tasks;
        foreach ($members as $key => $val) {
            $members[$key]['userid']=$val->user->id;
            $members[$key]['username']=$val->user->name;
            $members[$key]['user_pics']=$val->user->profile->avatar;
            unset($val->user);
        }
        foreach($tasks as $k =>$value) {
            $tasks[$k] = outputTranslate($value,ProjectTask::MAP_ARR);
        }
        $output['member_count'] = count($members);
        $output['member_list'] = $members;
        $output['task_list'] = $tasks;
//        $data = ['project'=>$info];
        return JsonBuilder::Success($output);
    }



    /**
     * 项目详情的修改
     * @param ProjectRequest $request
     * @return string
     */
    public function updateProject(ProjectRequest $request) {
        $project = $request->getProject();
        if(empty($project['id'])) {
            return JsonBuilder::Error('项目ID不能为空');
        }
        $projectId = $project['id'];
        unset($project['id']);
        $dao = new ProjectDao();
        $result = $dao->updateProject($projectId, $project);
        if($result->isSuccess()) {
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error($result->getMessage());
        }
    }

    public function taskList(ProjectRequest $request) {
        $user = $request->user();
        $data['school_id'] = $user->getSchoolId();
        $type = intval($request->type);
        if (!in_array($type, [1,2,3,4])) {
            return JsonBuilder::Error('没有内容');
        }
        $dao = new ProjectDao();
        $list = $dao->getTasks($user->id,$type);
        if (!$list)
        {
            return JsonBuilder::Error('没有内容');
        }
        $output = [];
        foreach ($list as $key => $val) {
            $output[$key]['taskid'] = $val->id;
            $output[$key]['create_userid'] = $val->create_user;
            $output[$key]['create_name'] = "管理员";
            $output[$key]['task_title'] = $val->title;
            $output[$key]['create_time'] = $val->created_at;
            $output[$key]['end_time'] = $val->end_time;
            $output[$key]['leader_userid'] = $val->user_id;
            $output[$key]['leader_name'] = $val->user->name;
            $output[$key]['status'] = $val->status;
        }
        return JsonBuilder::Success($output);
    }

    public function taskInfo(Request $request)
    {
        $user = $request->user();
        $data['school_id'] = $user->getSchoolId();
        $taskid = $request->get('taskid');
        $dao = new ProjectDao();
        $task = $dao->getProjectTaskById($taskid);
        if(!$task){
            return JsonBuilder::Error('没有内容');
        }
        $output = outputTranslate($task,ProjectTask::MAP_ARR);
/*        $output['create_userid'] = $task->create_user;
        $output['create_name'] = '管理员';
        $output['task_title'] = $task->title;
        $output['task_content'] = $task->content;
        $output['create_time'] = $task->created_at;
        $output['ent_time'] = $task->ent_time;
        $output['projectid'] = $task->project_id;
        $output['project_title'] = $task->project->title;
        $output['leader_userid'] = $task->user_id;
        $output['leader_name'] = $task->user->name;*/
        $output['report_btn'] = 1; //结果按钮 1-显示 0-隐藏
        $members = [];
        foreach ($task->project->members as $key => $val) {
            $members[$key]['userid']=$val->user->id;
            $members[$key]['username']=$val->user->name;
            $members[$key]['user_pics']=$val->user->profile->avatar;

        }
        $output['member_list'] = $members;
        $output['log_list'] = [];
        $forum = [];
        foreach ($task->discussions as $key => $val) {
            $forum[$key]['forumid']=$val->id;
            $forum[$key]['userid']=$val->user_id;
            $forum[$key]['username']=$val->user->name;
            $forum[$key]['user_pics']=$val->user->profile->avatar;
            $forum[$key]['forum_content']=$val->content;
            $forum[$key]['create_time']=$val->created_at;

        }
        $output['forum_list'] = $forum;
        return JsonBuilder::Success($output);

    }

    public function finishTask(Request $request)
    {
        $user = $request->user();
        $data['school_id'] = $user->getSchoolId();
        $dao = new ProjectDao();
        $taskId = $request->get('taskid');
        $taskremark = strip_tags($request->get('remark'));

        $task = $dao->getProjectTaskById($taskId);
        if ($task->user_id != $user->id)
            return JsonBuilder::Success('添加失败');
        $result = $dao->finishTask($taskId,$taskremark);
        if ($result)
        {
            return JsonBuilder::Success('添加成功');
        } else {
            return JsonBuilder::Success('添加失败');
        }
    }

    public function addOaTaskForum(Request $request)
    {
        $user = $request->user();
        $data['school_id'] = $user->getSchoolId();
        $dao = new ProjectDao();
        $taskid = intval($request->get('taskid'));
        $task = $dao->getProjectTaskById($taskid);
        if (!$task)
            return JsonBuilder::Success('添加失败');
        $forum_content = strip_tags($request->get('forum_content'));
        $userid = intval($request->get('userid'));
        $data = [
            'project_task_id' => $taskid,
            'user_id' => $user->id,
            'content' => $forum_content,
        ];
        $result = $dao->createDiscussion($data);
        if ($result)
        {
            return JsonBuilder::Success('添加成功');
        } else {
            return JsonBuilder::Success('添加失败');
        }

    }

    public function getOaTaskUserListInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $keyword = strip_tags($request->get('keyword'));
        $dao = new ProjectDao();

        $teachers = $dao->getTeachers($keyword, $schoolId);

        $output = [];
        foreach ($teachers as $key=>$value)
        {
            $output[$key]['userid'] = $value->user_id;
            $output[$key]['username'] = $value->name;
            $output[$key]['user_pics'] = $value->user->profile->avatar;
            $output[$key]['duties'] = $value->user->profile->group_name;
        }
        return JsonBuilder::Success($output);

    }

}
