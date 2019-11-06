<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\Users\UserDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Students\StudentProfileDao;
use App\Utils\JsonBuilder;

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
     * 专业详情
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function majorDetail(PlanRecruitRequest $request)
    {
        $majorId = $request->get('id');
        $schoolId = $request->get('school_id');
        $dao  = new RecruitmentPlanDao($schoolId);
        $data = $dao->getMajorDetailById($majorId);

        return JsonBuilder::Success($data);
    }

    /**
     * 已报名学生, 辅助填充数据
     * @param PlanRecruitRequest $request
     * @return string
     */
    public function studentProfile(PlanRecruitRequest $request)
    {
        $mobile = $request->get('mobile');
        $idNumber = $request->get('id_number');

        $userDao  = new UserDao;
        $studentProfileDao = new StudentProfileDao;

        if (isset($mobile)) {
            $user = $userDao->getUserByMobile($mobile);
            if (!empty($user->id)) {
                $userId = $user->id;
            }
        }

        if (isset($idNumber)) {
            $user = $studentProfileDao->getStudentInfoByIdNumber($idNumber);
            if (!empty($user->id)) {
                $userId = $user->user_id;
            }
        }

        $result = [];

        if (isset($userId)) {
            $field = ['id', 'user_id', 'id_number', 'gender', 'nation_name',
                  'political_name', 'source_place', 'country', 'birthday',
                  'qq', 'wx', 'parent_name', 'parent_mobile', 'examination_score'
            ];
            $result = $studentProfileDao->getStudentSignUpByUserId($userId, $field);
        }

        return JsonBuilder::Success($result);
    }

    /**
     * 学生报名
     * @param PlanRecruitRequest $request
     * @return string
     * @throws \Exception
     */
    public function signUp(PlanRecruitRequest $request)
    {
        $userId     = $request->get('id');
        $schoolId   = $request->get('school_id');
        $majorId    = $request->get('major_id');
        $planId     = $request->get('recruitment_plan_id');
        $data       = $request->get('data');

        $planDao = new RecruitmentPlanDao($schoolId);

        $plan = $planDao->getRecruitmentPlanById($planId);
        if ($plan->seats >= $plan->enrolled_count) {
            return JsonBuilder::Error('该专业已招满,请选择其他专业', '999');
        }

        $dao =  new RegistrationInformaticsDao;

        if (empty($userId)) {
            $add = $dao->addUser($data);
            if (!$add) {
               return JsonBuilder::Error('报名失败', '999');
            } else {
                 $signUpData['user_id']   = $add;
            }
        } else {
            $signUpData['user_id'] = $userId;
        }

        $signUpData['school_id']           = $schoolId;
        $signUpData['major_id']            = $majorId;
        $signUpData['name']                = $data['name'];
        $signUpData['relocation_allowed']  = $data['relocation_allowed'];
        $signUpData['recruitment_plan_id'] = $planId;
        $signUpData['status'] = 1;
        $result = $dao->signUp($signUpData);

        if ($result) {
            return JsonBuilder::Success('报名成功');
        } else {
            return JsonBuilder::Error('报名失败',999);
        }

    }

}
