<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ElectiveRequest;
use App\Dao\Users\GradeUserDao;
use App\Dao\Courses\CourseMajorDao;
use App\Models\Course;
use App\Models\Courses\CourseMajor;
use App\Models\Schools\Major;
use App\Models\Users\GradeUser;

class ElectiveController extends Controller
{

    public function index(ElectiveRequest $request)
    {
        $userId = $request->user()->id;

        $dao            = new GradeUserDao;
        $courseMajorDao = new CourseMajorDao;

        /**
         * @var GradeUser $userInfo
         */
        $userInfo = $dao->getUserInfoByUserId($userId);
//        $userInfo->major_id = 11;
//        $course = $courseMajorDao->getElectiveCourseByMajorId($userInfo->major_id);
//        dd($course->toArray());
        // 用户->专业->专业课程关联->课程->课程的设置
        /**
         * @var Major $major
         */
        $major = $userInfo->major;

        foreach ($major->courseMajors as $key => $courseMajor) {
            /**
             * @var CourseMajor $courseMajor
             */
            $teachers = $courseMajor->course->teachers;

            $course       = $courseMajor->course;
            $elective     = $course->courseElective;
            $studentCount = $course->studentEnrolledOptionalCourse->count();

            foreach ($teachers as $teacher) {
                $arrangements = $course->courseArrangements;
                $schedules = [];
                foreach ($arrangements as $arrangement) {
                    $s = ['weeks' => $arrangement->week, 'time' => $arrangement->day_index, 'location' => ''];
                    $schedules[] = $s;
                }
                $item = [
                    'course_name'  => $courseMajor->course,
                    'teacher_name' => $teacher->name,
                    'value'        => $course->scores,
                    'seats'        => $elective->open_num,
                    'applied'      => $studentCount,
                    'schedules'    => $schedules,
                    'expired_at'   => '', // Todo:
                    'threshold'    => 60, // Todo:
                    'introduction' => $course->desc
                ];

                dd(json_encode($item));
            }

        }

    }


}
