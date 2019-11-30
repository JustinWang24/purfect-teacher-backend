<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/11/19
 * Time: 2:10 PM
 */

namespace App\Dao\OA;


use App\Models\OA\Project;
use App\Models\OA\ProjectTask;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Collection;

class ProjectDao
{
    public function __construct()
    {
    }

    /**
     * 根据学校的 id 获取项目列表
     * @param $schoolId
     * @return Collection
     */
    public function getProjectsPaginateBySchool($schoolId){
        return Project::where('school_id',$schoolId)->
            orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param $id
     * @return Project
     */
    public function getProjectById($id){
        return Project::find($id);
    }

    /**
     * @param $taskId
     * @return ProjectTask
     */
    public function getProjectTaskById($taskId){
        return ProjectTask::find($taskId);
    }

    /**
     * 根据项目 ID 获取任务列表
     * @param $projectId
     * @return Collection
     */
    public function getTasksPaginateByProject($projectId){
        if($projectId){
            return ProjectTask::where('project_id',$projectId)
                ->orderBy('id','desc')
                ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        }else{
            return ProjectTask::orderBy('id','desc')
                ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        }
    }
}