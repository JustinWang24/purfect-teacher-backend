<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Courses\CourseMajorDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Controllers\Controller;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

class MajorsController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function load_by_school(Request $request){
        $majorDao = new MajorDao(new User());
        return JsonBuilder::Success(['majors'=>$majorDao->getMajorsBySchool($request->get('id'))]);
    }

    /**
     * 获取某个专业的所有班级
     * @param Request $request
     * @return string
     */
    public function load_major_grades(Request $request){
        $user = new User();
        $year = $request->get('year');
        if($year){
            $gradeDao = new GradeDao($user);
            $grades = $gradeDao->getGradesByMajorAndYear(
                $request->get('id'),
                $year
            );
            return JsonBuilder::Success(['grades'=>$grades]);
        }
        else{
            $majorDao = new MajorDao($user);
            $major = $majorDao->getMajorById($request->get('id'));
            return JsonBuilder::Success(['grades'=>$major->grades]);
        }
    }

    /**
     * 获取某个专业的所有课程. 如果提交的参数中包含了 as , 则根据 as 的逻辑提取课程
     * @param Request $request
     * @return string
     */
    public function load_major_courses(Request $request){
        $dao = new CourseMajorDao();
        if($request->has('as') && $request->get('as') === 'timetable-item-id'){
            $itemDao = new TimetableItemDao();
            $item = $itemDao->getItemById($request->get('itemId'));
            $courses = $dao->getCoursesByMajorAndTerm($item->grade->major_id, $item->term);
        }
        else{
            $courses = $dao->getCoursesByMajorAndTerm($request->get('id'), $request->get('term'));
        }
        return JsonBuilder::Success(['courses'=>$courses]);
    }
}
