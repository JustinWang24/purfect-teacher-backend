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
}
