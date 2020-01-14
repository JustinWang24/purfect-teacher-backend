<?php


namespace App\Http\Controllers\Api\OA;


use App\Dao\OA\TaskDao;
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
