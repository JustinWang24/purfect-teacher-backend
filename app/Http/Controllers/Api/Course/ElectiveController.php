<?php

namespace App\Http\Controllers\Api\Course;

use App\Dao\Courses\CourseDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ElectiveRequest;
use App\Dao\Users\GradeUserDao;
use App\Utils\JsonBuilder;
use PHPUnit\Util\Json;

class ElectiveController extends Controller
{

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
            $elective     = $course->courseElective;
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

        $teacher = $course->teachers;

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

    




}
