<?php

namespace App\Http\Controllers\Api\Course;

use App\Dao\Courses\CourseDao;
use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Schools\DepartmentDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ElectiveRequest;
use App\Dao\Users\GradeUserDao;
use App\Models\ElectiveCourses\CourseElective;
use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
use App\Models\Schools\SchoolConfiguration;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;

//@TODO ?为什么逻辑代码都在控制器 下面的流程不是理解
class ElectiveController extends Controller
{
    //选修课报名结果表前缀
    const STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME = 'student_enrolled_optional_courses';
    /**
     * 学生选课列表
     * @param ElectiveRequest $request
     * @return string
     */
    public function index(ElectiveRequest $request)
    {
        $userId = $request->user()->id;

        $dao = new GradeUserDao;

        $userInfo = $dao->getUserInfoByUserId($userId);

        $major = $userInfo->major;
        $item = [];

        $nowTime = Carbon::now()->timestamp;

        foreach ($major->courseMajors as $key => $courseMajor) {
            $course       = $courseMajor->course;

            $elective     = $course->courseElective()->first();
            //报名已满或被取消不显示
            if (!$elective || $elective->status == CourseElective::STATUS_ISFULL || $elective->status == CourseElective::STATUS_CANCEL)
            {
                continue;
            }
            $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME.'_'.$elective->start_year.'_'.$course->term;
            $electiveDao = new TeacherApplyElectiveCourseDao();
            $studentCount = $electiveDao->getEnrolledTotalForCourses($course->id); // 学生报名数量
            $studentCount += $electiveDao->getEnrolledResultTotalForCourse($course->id, $tableName);

            $arrangements = $course->courseArrangements;

            $schedules = [];
            foreach ($arrangements as $arrangement) {
                    $s = ['week' => $arrangement->week, 'day_index' => $arrangement->day_index, 'time' => $arrangement->timeslot->name];
                    $schedules[] = $s;
            }
            if ($nowTime < Carbon::parse($elective->enrol_start_at)->timestamp) {
                $status = 0;//未开始
            }elseif ($nowTime > Carbon::parse($elective->expired_at)->timestamp) {
                $status = 2;//已结束
            }else {
                $status = 1;//进行中
            }

            $item[]= [
                    'course_id'    => $course->id,
                    'course_name'  => $courseMajor->course_name,
                    'course_time'  => $schedules,
                    'scores'        => $course->scores,
                    'seats'        => $elective->max_num == 0 ? $elective->open_num : $elective->max_num,
                    'applied'      => $studentCount,
                    'expired_at'   => Carbon::parse($elective->expired_at)->format('Y-m-d H:i'),
                    'status'       => $status
            ];
        }

        return JsonBuilder::Success($item);
    }

    public function mylist(ElectiveRequest $request)
    {
        $retList = [];
        $user = $request->user();
        $electiveList = CourseElective::with('course')->get();
        $dao = new TeacherApplyElectiveCourseDao();
        foreach ($electiveList as $elective) {
            $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME.'_'.$elective->start_year.'_'.$course->term;
            $course = $elective->course;
            if (!$check = $dao->checkHasEnrolled($user, $course->id, $tableName)) {
                continue;
            }
            $studentCount = $dao->getEnrolledTotalForCourses($course->id); // 学生报名数量
            $studentCount += $dao->getEnrolledResultTotalForCourse($course->id, $tableName);

            $arrangements = $course->courseArrangements;
            $schedules = [];
            foreach ($arrangements as $arrangement) {
                $s = ['week' => $arrangement->week, 'day_index' => $arrangement->day_index, 'time' => $arrangement->timeslot->name];
                $schedules[] = $s;
            }

            $retList[]= [
                'course_id'    => $course->id,
                'course_name'  => $course->name,
                'course_time'  => $schedules,
                'scores'        => $course->scores,
                'seats'        => $elective->max_num == 0 ? $elective->open_num : $elective->max_num,
                'applied'      => $studentCount,
                'expired_at'   => Carbon::parse($elective->expired_at)->format('Y-m-d H:i'),
                'status'       => $elective->status == CourseElective::STATUS_CANCEL ? CourseElective::STATUS_CANCEL : $check->status
            ];
        }

        return JsonBuilder::Success($retList);
    }

    /**
     * 选修课详情
     * @param ElectiveRequest $request
     * @return string
     */
    public function details(ElectiveRequest $request)
    {
        $user = $request->user();
        $courseId = $request->get('course_id');

        $courseDao =  new CourseDao;

        $course = $courseDao->getCourseById($courseId);

        if (!$course) {
            return  JsonBuilder::Error('课程不存在');
        }
        $nowTime = Carbon::now()->timestamp;

        $teacher = $course->teachers[0]->name ?? null;

        $elective = $course->courseElective;

        $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME.'_'.$elective->start_year.'_'.$course->term;

        $dao = new TeacherApplyElectiveCourseDao();
        $studentCount = $dao->getEnrolledTotalForCourses($course->id); // 学生报名数量
        $studentCount += $dao->getEnrolledResultTotalForCourse($course->id, $tableName);

        $arrangements = $course->courseArrangements;

        $schedules = [];
        $config = (new SchoolDao())->getSchoolById($course->school_id)->configuration;
        $year = $elective->start_year . '-' . ($course->term == SchoolConfiguration::LAST_TERM ? SchoolConfiguration::FIRST_TERM_START_MONTH : SchoolConfiguration::SECOND_TERM_START_MONTH) . '-01';
        $weeks = $config->getAllWeeksOfTerm($course->term, false, $year);
        $weekList = [];
        foreach ($weeks as $week) {
            $weekList[$week->getname()] = $week;
        }
        $minDay = ['week' => 99, 'day' => ''];
        $maxDay = ['week' => 0, 'day' => ''];
        foreach ($arrangements as $arrangement) {
                if ($arrangement->week < $minDay['week']) {
                    $minDay = ['week' => $arrangement->week, 'day' => $weekList['第' . $arrangement->week . '周']->getstart()];
                }
                if ($arrangement->week > $maxDay['week']) {
                    $maxDay = ['week' => $arrangement->week, 'day' => $weekList['第' . $arrangement->week . '周']->getstart()];
                }
                $s = ['week' => $arrangement->week, 'day_index' => $arrangement->day_index, 'time' => $arrangement->timeslot->name, 'building' => $arrangement->building_name, 'classroom' => $arrangement->classroom_name];
                $schedules[] = $s;
        }

        //报名状态
        if ($nowTime < Carbon::parse($elective->enrol_start_at)->timestamp) {
            $enrollStatus = 0;//报名未开始
        }elseif ($nowTime > Carbon::parse($elective->expired_at)->timestamp) {
            $enrollStatus = 2;//报名已结束
        }else {
            $enrollStatus = 1;//报名进行中
        }

        //课程状态
        if($elective->status == CourseElective::STATUS_CANCEL) {
            $courseStatus = 3;//被取消
        }else {
            if ($nowTime < Carbon::parse($minDay['day'])->timestamp) {
                $courseStatus = 0;//待开课
            }elseif ($nowTime > Carbon::parse($maxDay['day'])->timestamp) {
                $courseStatus = 2;//已结束
            }else {
                $courseStatus = 1;//进行中
            }
        }


        //按钮状态
        $myenroll = StudentEnrolledOptionalCourse::where(['course_id' => $course->id, 'user_id' => $user->id])->first();
        $myenroll2 = DB::table($tableName)->where(['course_id' => $course->id, 'user_id' => $user->id])->first();
        if ($myenroll || $myenroll2) {
            $buttonStatus = 1;//已经报名
        }else {
            //先处理名额报满的情况
            $dao->operateEnrollResult($elective->max_num, $course->id, $tableName);
            $enrollNum = $dao->getTotalOfEnroll($user, $tableName);
            //得到用户可以报名的数量
            $numOfCanBeEnroll = $dao->getNumOfCanBeEnroll($user);
            //是否可以报名
            if($dao->quotaIsFull($course->id)) {
                $buttonStatus = 2;//报名人数已满
            }elseif ($enrollNum>=$numOfCanBeEnroll) {
                $buttonStatus = 3;//自己可报课程已满
            }else {
                $buttonStatus = 4;//可以报名
            }
        }

        $result  = [
            'course_id'    => $course->id,
            'course_name'  => $course->name,
            'teacher_name' => $teacher,
            'scores'        => $course->scores,
            'seats'        => $elective->max_num == 0 ? $elective->open_num : $elective->max_num,
            'applied'      => $studentCount,
            'course_time'    => $schedules,
            'expired_at'   => Carbon::parse($elective->expired_at)->format('Y-m-d H:i'),
            'threshold'    => $elective->open_num,
            'desc' => $course->desc,
            'enroll_status' => $enrollStatus,
            'course_status' => $courseStatus,
            'button_status' => $buttonStatus
        ];

        return JsonBuilder::Success($result);
    }

    /**
     * 先写报名表student_enrolled_optional_courses
     * 如果此课程报名数量已满，将从student_enrolled_optional_courses中导出此课程报名数据
     * 导入报名结果表，报名结果表是报名表+后缀的命名方式，后缀为开课年度_学期
     * @param Request $request
     * @param $courseId
     * @return string
     */

    public function enroll(Request $request, $courseId)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new TeacherApplyElectiveCourseDao();
        //查自己在报名结果表中的记录
        $courseDao =  new CourseDao;
        $course = $courseDao->getCourseById($courseId);
        if (!$course) {
            return  JsonBuilder::Error('课程不存在');
        }
        $teacherId = $course->teachers[0]->id;

        $elective = $course->courseElective;
        $start_year = $elective->start_year;
        $term = $course->term;

        $nowTime = Carbon::now()->timestamp;
        //报名状态
        if ($nowTime < Carbon::parse($elective->enrol_start_at)->timestamp) {
            $enrollStatus = 0;//报名未开始
        }elseif ($nowTime > Carbon::parse($elective->expired_at)->timestamp) {
            $enrollStatus = 2;//报名已结束
        }else {
            $enrollStatus = 1;//报名进行中
        }
        if ($enrollStatus != 1) {
            return JsonBuilder::Error('不在报名时间内');
        }

        $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME.'_'.$start_year.'_'.$term;

        //自己是否已经报名
        if ($dao->checkHasEnrolled($user, $courseId, $tableName)) {
            return JsonBuilder::Error('您已经报名了该课程');
        }

        $maxNum = $elective->max_num;
        //先处理名额报满的情况
        $dao->operateEnrollResult($maxNum, $courseId, $tableName);

        //得到用户的已报名数量
        $enrollNum = $dao->getTotalOfEnroll($user, $tableName);
        //得到用户可以报名的数量
        $numOfCanBeEnroll = $dao->getNumOfCanBeEnroll($user);
        if ($enrollNum>=$numOfCanBeEnroll) {
            return  JsonBuilder::Error('您的报名数量已满，不能再报其它课程');
        }
        //报名
        if ($dao->quotaIsFull($courseId)){
            return JsonBuilder::Error('此选修课报名数量已满');
        }

        //验证课程是否冲突

        $result = $dao->enroll($courseId, $user->id, $teacherId, $schoolId);
        return JsonBuilder::Success($result);

    }

    /**
     * 查询具体的报名结果
     * $result为空说明报名已满，当前用户报名失败
     * 否则返回排名具体情况
     * @param Request $request
     * @param $courseId
     * @return string
     */
    public function getEnrollResult(Request $request, $courseId)
    {
        $user = $request->user();
        $dao  = new TeacherApplyElectiveCourseDao();
        //查自己在报名结果表中的记录
        $courseDao =  new CourseDao;
        $course = $courseDao->getCourseById($courseId);
        if (!$course) {
            return  JsonBuilder::Error('课程不存在');
        }

        $elective = $course->courseElective;
        $start_year = $elective->start_year;
        $term = $course->term;
        $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME.'_'.$start_year.'_'.$term;
        $maxNum = $elective->max_num;

        //先处理名额报满的情况
        $isFull = $dao->operateEnrollResult($maxNum, $courseId, $tableName);
        if ($isFull)
        {
            $ranking = $dao->getRanking($user, $courseId, $tableName);
            if ($ranking) {
                //获取报名的具体记录
                $result = $dao->getResultEnrollRow($user, $courseId, $tableName);
            }
        } else {
            $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME;
            $ranking = $dao->getRanking($user, $courseId, $tableName);
            if ($ranking) {
                //获取报名的具体记录
                $result = $dao->getResultEnrollRow($user, $courseId, $tableName);
            }
        }
        //$result为空说明报名已满，当前用户报名失败
        //否则返回排名具体情况
        if(!empty($result))
        {
            $result = json_decode(json_encode($result), true);
            return JsonBuilder::Success($result);
        } else {
            return JsonBuilder::Error('课程报名人数已满，您没有报名成功，请选择其它课程报名');
        }
    }
}
