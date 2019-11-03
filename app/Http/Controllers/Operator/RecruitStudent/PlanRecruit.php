<?php

namespace App\Http\Controllers\Operator\RecruitStudent;

use App\Dao\Schools\MajorDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;

class PlanRecruit extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(PlanRecruitRequest $request)
    {
        //查询专业信息
        $majorDao = new MajorDao();
        $schoolId = $request->getSchoolId();
        $map = ['school_id'=>$schoolId];
        $field = ['id', 'name', 'description', 'fee', 'open', 'seats'];
        $list = $majorDao->getMajorPage($map, $field);
        $this->dataForView['major'] = $list;

        return view('school_manager.recruitStudent.planRecruit.management', $this->dataForView);
    }
}
