<?php

namespace App\Http\Controllers\Operator\OA;

use App\Dao\OA\ProjectDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{

    /**
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function management(MyStandardRequest $request){
        $dao = new ProjectDao();
        $this->dataForView['pageTitle'] = '办公/项目管理';
        $this->dataForView['projects'] = $dao->getProjectsPaginateBySchool($request->getSchoolId());
        return view('school_manager.oa.projects',$this->dataForView);
    }

    /**
     * 查看项目详情
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(MyStandardRequest $request){
        $dao = new ProjectDao();
        $this->dataForView['pageTitle'] = '办公/项目管理';
        $this->dataForView['project'] = $dao->getProjectById($request->uuid());
        return view('school_manager.oa.project_form',$this->dataForView);
    }

    public function tasks(MyStandardRequest $request){
        $dao = new ProjectDao();
        $this->dataForView['pageTitle'] = '办公/项目/任务管理';
        $this->dataForView['project'] = $dao->getProjectById($request->get('project_id'));
        $this->dataForView['tasks'] = $dao->getTasksPaginateByProject($request->get('project_id'));
        $this->dataForView['appendedParams'] = [
            'project_id'=>$request->get('project_id')
        ];
        return view('school_manager.oa.project_tasks',$this->dataForView);
    }

    public function task_view(MyStandardRequest $request){
        $dao = new ProjectDao();
        $this->dataForView['pageTitle'] = '办公/项目/任务管理';
        $projectTask = $dao->getProjectTaskById($request->get('task_id'));
        $this->dataForView['projectTask'] = $projectTask;
        $this->dataForView['project'] = $projectTask->project;
        return view('school_manager.oa.view_task',$this->dataForView);
    }
}
