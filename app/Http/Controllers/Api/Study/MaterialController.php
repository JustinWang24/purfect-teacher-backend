<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/9
 * Time: 上午11:19
 */

namespace App\Http\Controllers\Api\Study;


use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Dao\Courses\CourseTeacherDao;
use App\Dao\Courses\Lectures\LectureDao;
use App\Http\Requests\Course\MaterialRequest;

class MaterialController extends Controller
{


    /**
     * 我的课程
     * @param MaterialRequest $request
     * @return string
     */
    public function courses(MaterialRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $courseTeacherDao = new CourseTeacherDao();
        $courseTeacher = $courseTeacherDao->getCoursesByTeacher($user->id);
        if(count($courseTeacher) == 0) {
            return JsonBuilder::Success('您没有课程');
        }
        $lectureDao = new LectureDao();
        $types = $lectureDao->getMaterialType($schoolId);

        foreach ($types as $key => $val) {
            $num = $lectureDao->getMaterialNumByUserAndType($user->id, $val->type_id);
            $val->num = $num;
        }
        $courses = [];
        foreach ($courseTeacher as $key => $item) {
            $course = $item->course;
            $courses[] = [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'duration' => $course->duration,
                'desc' => $course->desc,
                'types' => $types
            ];
        }

        return JsonBuilder::Success($courses);
    }

}