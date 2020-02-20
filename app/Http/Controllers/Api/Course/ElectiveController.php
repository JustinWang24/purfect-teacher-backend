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
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;

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

        foreach ($major->courseMajors as $key => $courseMajor) {
            $course       = $courseMajor->course;

            $elective     = $course->courseElective()->first();
            $currentTime  = time();
            //报名已满和不在允许的报名时段内不展示
            if (!$elective || $elective->status == CourseElective::STATUS_ISFULL ||
                (!empty($elective->expired_at) && $currentTime > strtotime($elective->expired_at)) ||
                (!empty($elective->expired_at) && $currentTime < strtotime($elective->enrol_start_at)))
            {
                continue;
            }

            $electiveDao = new TeacherApplyElectiveCourseDao();
            $studentCount = $electiveDao->getEnrolledTotalForCourses($course->id); // 学生报名数量

            $arrangements = $course->courseArrangements;

            $schedules = [];
            foreach ($arrangements as $arrangement) {
                    $s = ['weeks' => $arrangement->week, 'time' => $arrangement->day_index, 'location' => ''];
                    $schedules[] = $s;
            }

            $item[]= [
                    'course_id'    => $course->id,
                    'course_name'  => $courseMajor->course_name,
                    'course_time'  => $schedules,
                    'value'        => $course->scores,
                    'seats'        => $elective->max_num,
                    'applied'      => $studentCount,
                    'expired_at'   => $elective->expired_at,
            ];
        }

        return JsonBuilder::Success($item);
    }

    /**
     * 选修课详情
     * @param ElectiveRequest $request
     * @return string
     */
    public function details(ElectiveRequest $request)
    {
        $courseId = $request->get('course_id');

        $courseDao =  new CourseDao;

        $course = $courseDao->getCourseById($courseId);

        if (!$course) {
            return  JsonBuilder::Error('课程不存在');
        }

        $teacher = $course->teachers[0]->teacher->name ?? null;

        $elective = $course->courseElective;

        $studentCount = $course->studentEnrolledOptionalCourse()->count();

        $arrangements = $course->courseArrangements;

        $schedules = [];
        foreach ($arrangements as $arrangement) {
                $s = ['weeks' => $arrangement->week, 'time' => $arrangement->day_index, 'location' => ''];
                $schedules[] = $s;
        }

        $result  = [
            'course_name'  => $course->name,
            'teacher_name' => $teacher,
            'value'        => $course->scores,
            'seats'        => $elective->open_num,
            'applied'      => $studentCount,
            'schedules'    => $schedules,
            'expired_at'   => $elective->expired_at,
            'threshold'    => $elective->max_num,
            'introduction' => $course->desc
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
        $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_TABLE_NAME.'_'.$start_year.'_'.$term;
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
