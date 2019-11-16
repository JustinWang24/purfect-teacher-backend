<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/11/19
 * Time: 9:45 PM
 */

namespace App\Http\Controllers\Teacher;


use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;

class ElectiveCoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(MyStandardRequest $request){
        $this->dataForView['pageTitle'] = '近期的开课申请';
        $schoolDao = new SchoolDao();
        $this->dataForView['configuration'] = $schoolDao->getSchoolById(session('school.id'))->configuration;
        $userDao = new UserDao();
        $this->dataForView['teacher'] = $userDao->getUserByUuid($request->uuid());
        $dao = new TeacherApplyElectiveCourseDao();
        $this->dataForView['applications'] = $dao->getAllBySchool(session('school.id'));
        return view('teacher.elective_course.form',$this->dataForView);
    }
}