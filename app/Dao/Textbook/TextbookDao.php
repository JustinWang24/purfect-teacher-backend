<?php
namespace App\Dao\Textbook;

use App\Dao\Courses\CourseDao;
use App\Dao\Courses\CourseMajorDao;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\GradeUserDao;
use App\Models\Acl\Role;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Models\Schools\RecruitmentPlan;
use App\Models\Schools\Textbook;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;

class TextbookDao
{

    /**
     * 创建
     * @param $data
     * @return MessageBag
     */
    public function create($data) {

        $info = $this->getTextbookByName($data['name']);
        if(!empty($info)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'该教材已添加,请勿重复添加');
        }

        $result = Textbook::create($data);

        if($result){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功',$result);
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'创建失败');
        }
    }


    /**
     * 根据ID修改
     * @param $data
     * @return mixed
     */
    public function editById($data) {

        return Textbook::where('id',$data['id'])->update($data);
    }



    /**
     * 根据名称获取教材
     * @param $name
     * @return mixed
     */
    public function getTextbookByName($name) {
        $field = ['id', 'name'];
        return Textbook::where('name',$name)->select($field)->first();
    }


    /**
     * 根据ID获取详情
     * @param $id
     * @return mixed
     */
    public function getTextbookById($id) {
        return Textbook::where('id',$id)->first();
    }


    /**
     * 通过专业ID获取教材采购数
     * @param $majorId
     * @param $schoolId
     * @return array
     */
    public function getTextbooksByMajor($majorId,$schoolId) {
        $courseMajorDao = new CourseMajorDao();
        $list = $courseMajorDao->getCoursesByMajor($majorId)->toArray();
        $courseIdArr = array_column($list,'id','id');
        //查询所有课程的详情
        $courseDao = new CourseDao();
        $field = ['id', 'code', 'name', 'year', 'term'];
        $courses = $courseDao->getCoursesByIdArr($courseIdArr, $field)->toArray();
        $thisYear = Carbon::now()->year;  // 今年
        $nextYear = Carbon::parse('+ 1year')->year; // 明年

        foreach ($courses as $key => $val) {
            $year = $nextYear - $val['year'];
            if($year == $thisYear) {
                // 去查招生计划和已招学生
                $num = $this->getNewlyBornNumByMajor($majorId,$nextYear,$schoolId);
                $courses[$key]['type'] = 1;   // 即将入学新生
                $courses[$key]['textbook_num'] = $num;
            } else {
                // 通过专业ID和课程的年级查询学生数量
                $courses[$key]['type'] = 0;   // 老生
                $num = $this->getStudentNumByMajorAndYear($majorId,$year);
                $courses[$key]['textbook_num'] = $num;
            }

        }

        return $courses;
    }


    /**
     * 通过该专业和学年获取班级学生的总数
     * @param $majorId
     * @param $year
     * @return int|mixed
     */
    public function getStudentNumByMajorAndYear($majorId, $year) {
        $gradeDao = new GradeDao();
        $gradeList = $gradeDao->getGradesByMajorAndYear($majorId, $year)->toArray();
        if(empty($gradeList)) {
            return 0;
        }
        $gradeIdArr = array_column($gradeList,'id');
        $gradeUserDao = new GradeUserDao();
        $map = ['user_type'=>Role::VERIFIED_USER_STUDENT];
        $count = $gradeUserDao->getCountByGradeIdArr($map,$gradeIdArr);
        return $count;
    }


    /**
     * 通过专业获取下年招生计划人数和已招人数
     * @param int $majorId 专业ID
     * @param int $year  年
     * @param int $schoolId  学校ID
     * @return array
     */
    public function getNewlyBornNumByMajor($majorId, $year, $schoolId) {
        //查询招生计划
        $recruitmentPlanDao = new RecruitmentPlanDao($schoolId);
        //统招计划
        $generalPlan = $recruitmentPlanDao->getRecruitmentPlanByMajorAndYear($majorId,$year,RecruitmentPlan::TYPE_GENERAL)->toArray();
        $generalPlanSeat = array_sum(array_column($generalPlan,'seats'));

        //自招计划
        $selfPlan = $recruitmentPlanDao->getRecruitmentPlanByMajorAndYear($majorId,$year,RecruitmentPlan::TYPE_SELF)->toArray();
        $selfPlanSeat = array_sum(array_column($selfPlan,'seats'));
        $totalPlanSeat = $generalPlanSeat + $selfPlanSeat;

        // 大于或等于该状态 表示已被录取
        $status = RegistrationInformatics::APPROVED;

        //统招报名人数
        $generalPlanIdArr = array_column($generalPlan,'id');
        $registrationInformaticsDao = new RegistrationInformaticsDao();
        $generalInformaticsSeat = $registrationInformaticsDao->getCountByStatusAndPlanIdArr($status, $generalPlanIdArr);


        //自招报名人数
        $selfPlanIdArr = array_column($selfPlan,'id');
        $selfInformaticsSeat = $registrationInformaticsDao->getCountByStatusAndPlanIdArr($status, $selfPlanIdArr);
        $totalInformaticsSeat = $generalInformaticsSeat + $selfInformaticsSeat;

        return [
            'general_plan_seat' => $generalPlanSeat,       // 计划统招人数
            'self_plan_seat'    => $selfPlanSeat,          // 计划自招人数
            'total_plan_seat'   => $totalPlanSeat,         // 计划总共招生人数
            'general_informatics_seat'  => $generalInformaticsSeat,  // 统招报名人数
            'self_informatics_seat'     => $selfInformaticsSeat,     // 自招报名人数
            'total_informatics_seat'    => $totalInformaticsSeat,    // 总共报名人数
            ];
    }


    /**
     * 获取课程列表
     * @param $schoolId
     * @return mixed
     */
    public function getTextbookListBySchoolId($schoolId) {
        return Textbook::where('school_id',$schoolId)->with('course')->get();
    }



}
