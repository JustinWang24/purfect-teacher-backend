<?php

namespace App\Http\Controllers\Api\Course;

use App\Dao\Courses\CourseDao;
use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Schools\DepartmentDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ElectiveRequest;
use App\Dao\Users\GradeUserDao;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;

class ElectiveController extends Controller
{
    //选修课报名结果表前缀
    const STUDENT_ENROLLED_OPTIONAL_COURSES_PREFIX = 'student_enrolled_optional_courses';
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

            $elective     = $course->courseElective()->get();
            $elective     = $elective[0];

            $studentCount = $course->studentEnrolledOptionalCourse->count(); // 学生报名数量

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

        $teacher = $course->teachers[0];

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
            'teacher_name' => $teacher->tercher_name,
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


    public function enroll(Request $request, $courseId)
    {
        $user = $request->user();
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
        $tableName = self::STUDENT_ENROLLED_OPTIONAL_COURSES_PREFIX.'_'.$start_year.'_'.$term;
        $result = $dao->createEnrollTable($tableName);
        if (!$result)
        {
            return  JsonBuilder::Error('系统错误请联系系统管理员');
        }
        //得到用户的已报名数量
        $enrollNum = $dao->getTotalOfEnroll($user, $tableName);
        //得到用户可以报名的数量
        $numOfCanBeEnroll = $dao->getNumOfCanBeEnroll($user);
        if ($enrollNum>=$numOfCanBeEnroll) {
            return  JsonBuilder::Error('您的报名数量已满，不能再报其它课程');
        }
        //报名
        if ($dao->quotaIsFull($courseId)){
            JsonBuilder::Error('此选修课报名数量已满');
        }
        $result = $dao->enroll($courseId, $user->id, $teacherId);
        return JsonBuilder::Success($result);

    }





}
