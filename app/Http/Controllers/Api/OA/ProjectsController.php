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
use function Couchbase\defaultDecoder;
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
     * 项目列表
     * @param ProjectRequest $request
     * @return string
     */
    public function projectList(ProjectRequest $request) {
        $userId = $request->user()->id;
        $schoolId = $request->user()->getSchoolId();
        $dao = new ProjectDao();
        $list = $dao->getProjects($schoolId);
        $data = [];
        foreach ($list as $key => $item) {
//            $tasks = $item->tasks;
//            $re = $tasks->first();
            $end_time = $re->end_time ?? '';
            $members = $item->members;
            $memberIds = $members->pluck('user_id')->toArray(); // 项目成员
            array_push($memberIds,$item->user_id);
            $memberIds = array_unique($memberIds);   // 项目总成员
            $userIds = array_merge($memberIds, [$item->create_user]);  // 可见人员 成员、负责人、创建者
//            $statusArr = $tasks->pluck('status')->toArray();
//            if(empty($statusArr)) {
//                $status = Project::STATUS_NOT_BEGIN;  // 未开始
//
//            } elseif(in_array(ProjectTask::STATUS_IN_PROGRESS,$statusArr)) {
//                $status = Project::STATUS_IN_PROGRESS; // 正在进行
//
//            } else {
//                $status = Project::STATUS_CLOSED; // 已结束
//            }

            if($item->is_open == Project::OPEN || in_array($userId, $userIds)) {
                $data[] = [
                    'projectid'=>$item->id,
                    'project_title' => $item->title,
                    'end_time' => $end_time,
                    'leader_userid' => $item->user->id,
                    'leader_name' => $item->user->name,
                    'member_count' => count($memberIds),
                    'doing_status' => $item->status,
                ];
            }

        }

        return JsonBuilder::Success($data);
    }


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
        $projectId = $request->getProjectId();
        if(is_null($projectId)) {
            return JsonBuilder::Error('项目ID不能为空');
        }
        $dao = new ProjectDao();
        $info = $dao->getProjectById($projectId);

        if (!$info){
            return JsonBuilder::Error('没有这个项目');
        }

        $output['projectid'] = $info->id;
        $output['project_title'] = $info->title;
        $output['project_content'] = $info->content;
        $output['create_time'] = $info->created_at;
        $output['leader_userid'] = $info->user_id;
        $output['leader_name'] = $info->user->name;
        $output['create_userid'] = $info->create_user;
        $output['create_name'] = $info->createUser->name;
        $output['is_open'] = $info->is_open;

        $members = $info->members;
        $tasks = $info->tasks;
        $memberList = [];
        foreach ($members as $key => $val) {
            $memberList[$key]['userid']=$val->user->id;
            $memberList[$key]['username']=$val->user->name;
            $memberList[$key]['user_pics']=$val->user->profile->avatar;
        }

        $taskList = [];
        foreach ($tasks as $key => $item) {
            $taskList[$key]['taskid'] = $item->id;
            $taskList[$key]['task_title'] = $item->title;
            $status = $item->status;
            if($item->status == ProjectTask::STATUS_CLOSED) {
                $taskMembers = $item->taskMembers;
                foreach ($taskMembers as $k => $v) {
                    if($v->end_time > $item->end_time) {
                        $status = ProjectTask::STATUS_OVERTIME;  // 任务超时
                        break;
                    }
                }
            }

            $taskList[$key]['doing_status'] = $status;


        }

        $output['member_count'] = count($memberList);
        $output['member_list'] = $memberList;
        $output['task_list'] = $taskList;
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
