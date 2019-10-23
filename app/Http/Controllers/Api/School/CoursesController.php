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
        $result = false;
        if(empty($courseData['id'])){
            // 创建新课程
            $result = $dao->createCourse($courseData);
        }
        else{
            // Todo 更新操作
        }

        return $result ?
            JsonBuilder::Success() : JsonBuilder::Error();
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
