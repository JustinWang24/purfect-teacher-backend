<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Courses\CourseDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoursesController extends Controller
{
    public function save_course(Request $request){
        $courseData = $request->get('course');
        $courseData['school_id'] = $request->get('school');

        $dao = new CourseDao();
        if(empty($courseData['id'])){
            // 创建新课程
            $result = $dao->createCourse($courseData);
        }
        else{
            // 更新操作
            $result = $dao->updateCourse($courseData);
        }

        $course = $result->getData();



        return $result->isSuccess() ?
            JsonBuilder::Success(['id'=>$course->id ?? $courseData['id']])
            : JsonBuilder::Error($result->getMessage());
    }

    /**
     * @param Request $request
     * @return string
     */
    public function delete_course(Request $request){
        $courseUuid = $request->get('course');
        $dao = new CourseDao();
        $result = $dao->deleteCourseByUuid($courseUuid);
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function load_courses(Request $request){
        $schoolId = $request->get('school');
        $dao = new CourseDao();
        return JsonBuilder::Success(['courses'=>$dao->getCoursesBySchoolId($schoolId)]);
    }
}
