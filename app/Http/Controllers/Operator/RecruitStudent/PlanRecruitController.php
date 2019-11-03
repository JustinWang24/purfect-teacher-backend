<?php

namespace App\Http\Controllers\Operator\RecruitStudent;

use App\Utils\FlashMessageBuilder;
use App\Http\Controllers\Controller;
use App\Dao\RecruitStudent\PlanRecruitDao;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;

class PlanRecruitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(PlanRecruitRequest $request)
    {
        //查询专业信息
        $schoolId = $request->getSchoolId();
        $planRecruitDao = new PlanRecruitDao();
        $list = $planRecruitDao->getPlanRecruitBySchoolId($schoolId);
        $this->dataForView['major'] = $list;

        return view('school_manager.recruitStudent.planRecruit.list', $this->dataForView);
    }


    public function edit(PlanRecruitRequest $request) {
        $planRecruitDao = new PlanRecruitDao();
        if($request->isMethod('post')) {
            $user = $request->user();
            $all = $request->post('major');
            $result = $planRecruitDao->updPlanRecruit($all, $user);
            if($result !== false) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
            } else {
                 FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }
            return redirect()->route('school_manager.planRecruit.list');

        }

        $majorId = $request->get('majorId');
        $info = $planRecruitDao->getPlanRecruitInfo($majorId);
        $this->dataForView['major'] = $info;
        return view('school_manager.recruitStudent.planRecruit.edit', $this->dataForView);
    }
}
