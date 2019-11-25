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

    public function view(MyStandardRequest $request){
        $dao = new ProjectDao();
        $this->dataForView['pageTitle'] = '办公/项目管理';
        $this->dataForView['project'] = $dao->getProjectById($request->uuid());
        return view('school_manager.oa.project_form',$this->dataForView);
    }
}
