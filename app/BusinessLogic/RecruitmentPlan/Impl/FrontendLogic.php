<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/11/19
 * Time: 6:10 PM
 */

namespace App\BusinessLogic\RecruitmentPlan\Impl;
use App\BusinessLogic\RecruitmentPlan\Contract\IPlansLoaderLogic;
use App\Dao\Courses\CourseDao;
use App\Dao\Courses\CourseMajorDao;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\UserDao;
use App\Http\Requests\RecruitStudent\PlanRecruitRequest;
use Carbon\Carbon;

class FrontendLogic implements IPlansLoaderLogic
{
    private $schoolUuId;
    private $schoolId;
    private $today;
    private $request;
    private $studentMobile;
    private $studentIdNumber;

    public function __construct(PlanRecruitRequest $request)
    {
        $this->userId = null;
        $this->schoolUuId = $request->getSchoolId();
        $this->schoolId = null;

        if(empty($this->schoolUuId)){
            $user = $request->user('api');
            if($user){
                $this->userId = $user->id;
                $this->schoolId = $user->getSchoolId();
            }
        }

        $this->today = Carbon::today();
        $this->request = $request;
        $this->studentIdNumber = $request->getStudentIdNumber();
        $this->studentMobile = $request->getMobile();
    }

    public function getPlans()
    {
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolByUuid($this->schoolUuId);

        if(!$school){
            $school = $schoolDao->getSchoolById($this->schoolId);
        }

        if(!$school){
            return [];
        }
        $dao = new RecruitmentPlanDao($school->id);

        $rows = $dao->getPlansBySchoolForToday($this->today, $school->id);
        $plans = [];

        $profileDao = new StudentProfileDao();
        $userId = $profileDao->getUserIdByIdNumberOrMobile($this->studentIdNumber, $this->studentMobile);
        if($rows){
          $regDao = new RegistrationInformaticsDao();
            foreach ($rows as $row) {
              // 获取我是否可以报名
              $statusMessageArr = $regDao->getRegistrationInformaticsStatusInfo($this->userId, $row);
              $applied = $statusMessageArr['status'] == 100 ? false : $statusMessageArr['message1'];

                // 返回数据
                $plans[] = [
                    'id'=>$row->id,
                    'name'=>$row->major->name,
                    'fee'=>$row->fee,
                    'period'=>$row->major->period,
                    'seats'=>$row->seats, // 招生人数
                    'applied_count'=>$row->applied_count, // 报名人数
                    'enrolled'=>$row->enrolled_count, // 已招生人数
                    'applied'=>$applied,
                    'hot'=>$row->hot,
                    'title'=>$row->title,
                    'tease'=>$row->tease,
                ];
            }
        }

        return $plans;
    }

    public function getPlanDetail()
    {
        $planId = $this->request->get('id');
        $dao = new RecruitmentPlanDao(0);
        $plan = $dao->getPlan($planId);

        $courseDao = new CourseMajorDao();
        $courses = $courseDao->getCoursesByMajor($plan->major_id);

        return [
            'id'=>$plan->id,
            'name'=>$plan->major->name,
            'campus'=>$plan->major->campus->name??'主校区',
            'fee'=>$plan->fee,
            'period'=>$plan->major->period,
            'seats'=>$plan->seats,
            'enrolled'=>$plan->enrolled_count,
            'applied'=>$plan->applied_count,
            'hot'=>$plan->hot,
            'title'=>$plan->title,
            'description'=>$plan->description,
            'how_to_enrol'=>$plan->how_to_enrol,
            'student_requirements'=>$plan->student_requirements,
            'target_students'=>$plan->target_students,
            'future'=>$plan->major->future,
            'courses'=>$courses
        ];
    }
}
