<?php

namespace App\Http\Controllers\Teacher;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Students\StudentProfileDao;
use App\Http\Requests\Teacher\RecruitmentRegistrationFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;
use App\Dao\RecruitStudent\PlanRecruitDao;
use App\User;
use App\Utils\FlashMessageBuilder;

class RecruitmentFormsController extends Controller
{
    public function __construct()
    {
        $this->dataForView['pageTitle'] = '报名管理';
    }

    public function list(PlanRecruitRequest $request)
    {
        //查询专业信息
        $schoolId = $request->getSchoolId();
        $planRecruitDao = new PlanRecruitDao();
        $list = $planRecruitDao->getPlanRecruitBySchoolId($schoolId);
        $this->dataForView['major'] = $list;

        return view('school_manager.recruitStudent.planRecruit.management', $this->dataForView);
    }

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
        if($request->needAllStatus()){
            // 获取所有的申请表
            $this->dataForView['registrations'] = $registrationDao
                ->getPaginatedByPlanIdForAll($request->getPlanId());
        }elseif($request->isInWaitingStatus()){
            // 加载所有 待批准 的申请表
            $this->dataForView['registrations'] = $registrationDao
                ->getPaginatedRegistrationsWaitingForApprovalByPlanId($request->getPlanId());
        }elseif($request->isInRefusedStatus()){
            // 加载所有 拒绝 的申请表
            $this->dataForView['registrations'] = $registrationDao
                ->getPaginatedRegistrationsRefusedByPlanId($request->getPlanId());
        }

        $this->dataForView['requestStatus'] = $request->getStatus();
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
        if($request->isInApprovedStatus()){
            $this->dataForView['registrations'] = $registrationDao
                ->getPaginatedApprovedByPlanId($request->getPlanId());
            $this->dataForView['appendedParams'] = ['plan'=>$request->getPlanId(),'status'=>'approved'];
            return view('teacher.recruitment.registration_form.new_students',$this->dataForView);
        }else{
            $this->dataForView['registrations'] = $registrationDao
                ->getPaginatedPassedByPlanIdForApproval($request->getPlanId());
            $this->dataForView['appendedParams'] = ['plan'=>$request->getPlanId()];
            return view('teacher.recruitment.registration_form.enrol',$this->dataForView);
        }
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

    /**
     * 删除报名表
     *
     * @param RecruitmentRegistrationFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(RecruitmentRegistrationFormRequest $request){
        $formId = $request->uuid();
        $registrationDao = new RegistrationInformaticsDao();
        $result = $registrationDao->delete($formId, $request->user());
        if($result->isSuccess()){
            FlashMessageBuilder::Push($request,FlashMessageBuilder::SUCCESS,$result->getData()->name.'的申请已经被删除');
        }
        else{
            FlashMessageBuilder::Push($request,FlashMessageBuilder::DANGER,$result->getMessage());
        }
        return redirect(url()->previous());
    }

    /**
     * 打印学生的录取通知书
     *
     * @param RecruitmentRegistrationFormRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print_invitation(RecruitmentRegistrationFormRequest $request){
        $formId = $request->uuid();
        $registrationDao = new RegistrationInformaticsDao();
        $this->dataForView['form'] = $registrationDao->getById($formId);
        return view('student.registration.invitation',$this->dataForView);
    }

    /**
     * @param RecruitmentRegistrationFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancel_enrolment(RecruitmentRegistrationFormRequest $request){
        $formId = $request->uuid();
        $registrationDao = new RegistrationInformaticsDao();
        $result = $registrationDao->cancelEnrolment($formId, $request->user());
        if($result->isSuccess()){
            FlashMessageBuilder::Push($request,FlashMessageBuilder::SUCCESS,$result->getData()->name.'的入学资格已经被取消');
        }
        else{
            FlashMessageBuilder::Push($request,FlashMessageBuilder::DANGER,$result->getMessage());
        }
        return redirect(url()->previous());
    }
}
