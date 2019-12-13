<?php


namespace App\Http\Controllers\Api\OA;


use App\Utils\JsonBuilder;
use App\Dao\OA\ProjectDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\OA\ProjectRequest;

class ProjectsController extends Controller
{

    /**
     * 创建项目
     * @param ProjectRequest $request
     * @return string
     */
    public function createProject(ProjectRequest $request) {
        $project = $request->getProject();
        $member  = $request->getMembers();
        $dao = new ProjectDao();
        $result = $dao->createProject($project, $member);
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
        $task = $request->getTask();
        $dao = new ProjectDao();
        $result = $dao->createTask($task);
        if($result->isSuccess()) {
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
        $dao = new ProjectDao();
        $list = $dao->getProjectByUserId($userId);
        $data = pageReturn($list);
        return JsonBuilder::Success($data);
    }


    /**
     * 查看项目下的任务列表
     * @param ProjectRequest $request
     * @return string
     */
    public function taskList(ProjectRequest $request) {
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
        $members = $info->members;
        foreach ($members as $key => $val) {
            $val->user_field = ['name'];
            $members[$key]=$val->user;
        }
        $data = ['project'=>$info];
        return JsonBuilder::Success($data);
    }

}
