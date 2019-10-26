<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Courses\CourseTeacherDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeachersController extends Controller
{
    /**
     * 根据给定的姓名, 搜索老师的方法
     * @param Request $request
     * @return string
     */
    public function search_by_name(Request $request){
        // 获取需要搜索的教师姓名
        $name = $request->get('query');
        // 获取限定的学校的 ID
        $schoolId = $request->get('school');
        // 限定所选择的专业
        $majorsId = $request->get('majors');

        $dao = new TeacherProfileDao();

        // 搜索过程, 先简单处理, 在数据量比较小的情况下, 直接搜索 teacher profiles 表, 而不考虑 major 来缩小范围
        $result = $dao->searchTeacherByNameSimple($name, $schoolId);
        return JsonBuilder::Success(['teachers'=>$result]);
    }

    public function load_course_teachers(Request $request){
        $dao = new CourseTeacherDao();
        return JsonBuilder::Success(['teachers'=>$dao->getTeachersByCourse($request->get('course'))]);
    }
}
