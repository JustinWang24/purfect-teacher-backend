<?php

namespace App\Http\Controllers\Teacher;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Students\StudentProfileDao;
use App\Http\Requests\Teacher\RecruitmentRegistrationFormRequest;
use App\Http\Controllers\Controller;

class RecruitmentFormsController extends Controller
{
    public function __construct()
    {
        $this->dataForView['pageTitle'] = '报名管理';
    }
    //
    /**
     * 老师用来管理报名表的 action, 它包含了是否批准某个报名表可以通过, 以便下一步决定是否可以入学的
     *
     * @param RecruitmentRegistrationFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manage(RecruitmentRegistrationFormRequest $request){
        $planDao = new RecruitmentPlanDao(session('school.id'));
        $this->dataForView['plan'] = $planDao->getPlan($request->getPlanId());
        $this->dataForView['pageTitle'] = $this->dataForView['plan']->title . '管理';

        $registrationDao = new RegistrationInformaticsDao();
        $this->dataForView['registrations'] = $registrationDao
            ->getPaginatedRegistrationsByPlanIdForApproval($request->getPlanId());
        $this->dataForView['appendedParams'] = ['plan'=>$request->getPlanId()];

        return view('teacher.recruitment.registration_form.list',$this->dataForView);
    }

    /**
     * 老师用来录取报名表的 action, 它包含了是否批准某个报名表可以通过, 以便下一步决定是否可以入学的
     *
     * @param RecruitmentRegistrationFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enrol(RecruitmentRegistrationFormRequest $request){
        $planDao = new RecruitmentPlanDao(session('school.id'));
        $this->dataForView['plan'] = $planDao->getPlan($request->getPlanId());
        $this->dataForView['pageTitle'] = $this->dataForView['plan']->title . '录取';

        $registrationDao = new RegistrationInformaticsDao();
        $this->dataForView['registrations'] = $registrationDao
            ->getPaginatedPassedByPlanIdForApproval($request->getPlanId());
        $this->dataForView['appendedParams'] = ['plan'=>$request->getPlanId()];

        return view('teacher.recruitment.registration_form.enrol',$this->dataForView);
    }

    /**
     * 查看学生的报名资料
     * @param RecruitmentRegistrationFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_student_profile(RecruitmentRegistrationFormRequest $request){

        $profileDao = new StudentProfileDao();
        $planDao = new RecruitmentPlanDao(session('school.id'));
        $registrationDao = new RegistrationInformaticsDao();

        // 获取招生简章
        $this->dataForView['plan'] = $planDao->getPlan($request->getPlanId());

        // 获取学生档案
        $profile = $profileDao->getProfileByUuid($request->uuid());
        $this->dataForView['profile'] = $profile;

        // 获取学生填报的报名表
        $this->dataForView['registration'] = $registrationDao->getInformaticsByUserIdAndPlanId($profile->user_id, $request->getPlanId());
        $this->dataForView['student'] = $profile->user;

        $this->dataForView['pageTitle'] = $profile->user->name.'的报名表';

        return view('student.profile.view_by_teacher',$this->dataForView);
    }
}
