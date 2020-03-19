<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\Events\SystemNotification\ApproveOpenMajorEvent;
use App\Events\User\Student\ApplyRecruitmentPlanEvent;
use App\Events\User\Student\ApproveRegistrationEvent;
use App\Events\User\Student\EnrolRegistrationEvent;
use App\Events\User\Student\RefuseRegistrationEvent;
use App\Events\User\Student\RejectRegistrationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\Users\UserDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Students\StudentProfileDao;
use App\Utils\JsonBuilder;
use App\Utils\Time\GradeAndYearUtil;


class OpenMajorController extends Controller
{
    /**
     * 招生专业
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function major(PlanRecruitRequest $request)
    {
        $schoolId = $request->get('school_id');
        $mobile = $request->get('mobile');
        $idNumber = $request->get('id_number');

        $dao  = new RecruitmentPlanDao($schoolId);
        $userDao  = new UserDao;
        $informaticsDao = new RegistrationInformaticsDao;
        $studentProfileDao = new StudentProfileDao;

        $data = $dao->getAllMajorBySchoolId($schoolId);

        $info = [];
        if (isset($mobile)) {
            $user = $userDao->getUserByMobile($mobile);
            if (!empty($user->id)) {
                $info = $informaticsDao->getInformaticsByUserId($user->id)->toArray();
            }
        }
        if (isset($idNumber)) {
            $user = $studentProfileDao->getStudentInfoByIdNumber($idNumber);
            if (!empty($user->user_id)) {
                $info = $informaticsDao->getInformaticsByUserId($user->user_id)->toArray();
            }
        }

        if (empty($info)) {
            foreach ($data as $key => $val) {
                $data[$key]['status'] = 0;
                unset($data[$key]['major_id']);
            }
        } else {
            $majorIdArr = array_column($info, 'major_id','major_id');
            foreach ($data  as $key => $val ) {
                if(in_array($val['major_id'],$majorIdArr)) {
                    $data[$key]['status'] = 1;
                } else {
                    $data[$key]['status'] = 0;
                }
                unset($data[$key]['major_id']);
            }
        }

        return JsonBuilder::Success(['majors'=> $data ?? []]);
    }

    /**
     * 学生尝试加载自己已经报名过的招生信息
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function studentProfile(PlanRecruitRequest $request)
    {
        $mobile = $request->getMobile();            // 手机号
        $idNumber = $request->getStudentIdNumber(); // 身份证号

        $userId = null;
        $userProfile = null;

        // 优先通过提交的身份证进行查询
        if ($idNumber) {
            $studentProfileDao = new StudentProfileDao;
            $userProfile = $studentProfileDao->getStudentInfoByIdNumber($idNumber);
            $userId = $userProfile->user_id ?? null;
        }

        // 如果这个时候没有取到用户
        if (!$userId && $mobile) {
            $userDao  = new UserDao;
            $user = $userDao->getUserByMobile($mobile);
            $userId = $user->id ?? null;
        }
        $result = [];

        if ($userId) {
            $regDao = new RegistrationInformaticsDao();
            $result = $regDao->getInformaticsByUserId($userId, $simple = true); // 获取简单的数据即可
        }

        return JsonBuilder::Success(['applied'=>$result]);
    }

    /**
     * 学生报名
     * @param PlanRecruitRequest $request
     * @return string
     * @throws \Exception
     */
    public function signUp(PlanRecruitRequest $request)
    {
        $formData = $request->getSignUpFormData();
        $plan = $request->getPlan();

        if ($plan->seats <= $plan->enrolled_count) {
            return JsonBuilder::Error('该专业已招满,请选择其他专业');
        }

        $dao =  new RegistrationInformaticsDao;

        $profileDao = new StudentProfileDao();
        $userId = $profileDao->getUserIdByIdNumberOrMobile($formData['id_number'], $formData['mobile']);

        if (!$userId) {
            $msgBag = $dao->addUser($formData, $plan);
            if ($msgBag->isSuccess()) {
                $user = $msgBag->getData()['user'];
            } else {
                return JsonBuilder::Error($msgBag->getMessage());
            }
        } else {
            $userDao = new UserDao();
            $user = $userDao->getUserByIdOrUuid($userId);
        }
        /**
         * signUp 中会执行包括报名总人数更新, 消息通知发布的功能
         */
        $result = $user ? $dao->signUp($formData, $user) : false;

        if ($result && $result->isSuccess()) {
            // 通知老师, 有个新报名的学生
            event(new ApplyRecruitmentPlanEvent($result->getData()));
            return JsonBuilder::Success('报名成功');
        } else {
            return JsonBuilder::Error('报名失败');
        }
    }

    /**
     * 批准或者拒绝某个报名表格
     *
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function approve_or_reject(PlanRecruitRequest $request){
        $form = $request->getApprovalForm();
        $userUuid = $request->uuid();
        $userDao = new UserDao();
        $manager = $userDao->getUserByUuid($userUuid);
        if($manager && ($manager->isSchoolAdminOrAbove() || $manager->isTeacher())){
            // 操作者至少应该是学校的员工
            $dao = new RegistrationInformaticsDao();
            if($request->isApprovedAction()){
                $bag = $dao->approve($form['currentId'],$manager,$form['note']??null);

                event(new ApproveOpenMajorEvent($bag->getData()));
                event(new ApproveRegistrationEvent($bag->getData()));
            }else{
                $bag = $dao->refuse($form['currentId'],$manager,$form['note']??null);

                event(new ApproveOpenMajorEvent($bag->getData()));
                event(new RefuseRegistrationEvent($bag->getData()));
            }
            if($bag->isSuccess()){
                return JsonBuilder::Success($bag->getMessage());
            }
            else{
                return JsonBuilder::Error($bag->getMessage());
            }
        }
        return JsonBuilder::Error('无权执行此操作');
    }

    /**
     * 录取学生
     *
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function enrol_or_reject(PlanRecruitRequest $request){
        $form = $request->getApprovalForm();
        $userUuid = $request->uuid();
        $userDao = new UserDao();
        $manager = $userDao->getUserByUuid($userUuid);
        if($manager && ($manager->isSchoolAdminOrAbove() || $manager->isTeacher())){
            // 操作者至少应该是学校的员工
            $dao = new RegistrationInformaticsDao();
            if($request->isEnrolAction()){
                $bag = $dao->enrol($form['currentId'],$manager,$form['note']??null);
                event(new EnrolRegistrationEvent($bag->getData()));
            }else{
                $bag = $dao->reject($form['currentId'],$manager,$form['note']??null);
                event(new RejectRegistrationEvent($bag->getData()));
            }
            if($bag->isSuccess()){
                return JsonBuilder::Success($bag->getMessage());
            }
            else{
                return JsonBuilder::Error($bag->getMessage());
            }
        }
        return JsonBuilder::Error('无权执行此操作');
    }

    /**
     * 验证身份证号码是符合规范的
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function verify_id_number(PlanRecruitRequest $request){
        $idNumber = $request->get('id_number');
        $bag = GradeAndYearUtil::IdNumberToBirthday($idNumber);
        if($bag->isSuccess()){
            return JsonBuilder::Success(_printDate($bag->getData()));
        }else{
            return JsonBuilder::Error($bag->getMessage());
        }
    }

}
