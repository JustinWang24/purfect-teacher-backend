<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Http\Requests\SchoolRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\Models\Acl\Role;
use App\Utils\FlashMessageBuilder;
use App\Dao\Schools\InstituteDao;

class SchoolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理员选择某个学校作为操作对象
     * @param SchoolRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enter(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        // 获取学校
        $school = $dao->getSchoolByUuid($request->uuid());
        $school->savedInSession($request);
        return redirect()->route('school_manager.school.view');
    }

    /**
     * 更新学校的配置信息
     * @param SchoolRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function config_update(SchoolRequest $request){
        $dao = new SchoolDao($request->user());
        $school = $dao->getSchoolByUuid($request->uuid());
        // 要比较学校中每个系的相同的配置项目, 如果学校的要求高于系的要求, 那么就要覆盖系的. 如果低于系的要求, 那么就保留
        if($school){
            $dao->updateConfiguration($request->getConfiguration(), $school);
            FlashMessageBuilder::Push($request, 'success','配置已更新');
        }
        else{
            FlashMessageBuilder::Push($request, 'danger','无法获取学校数据');
        }
        return redirect()->route('school_manager.school.view');
    }

    public function institutes(SchoolRequest $request){
        $instituteDao = new InstituteDao($request->user());
        $this->dataForView['institutes'] = $instituteDao->getBySchool(session('school.id'));
        return view('school_manager.school.institutes', $this->dataForView);
    }

    public function departments(SchoolRequest $request){
        $dao = new DepartmentDao($request->user());
        $this->dataForView['departments'] = $dao->getBySchool(session('school.id'));
        return view('school_manager.school.departments', $this->dataForView);
    }

    public function majors(SchoolRequest $request){
        $dao = new MajorDao($request->user());
        $this->dataForView['majors'] = $dao->getBySchool(session('school.id'));
        return view('school_manager.school.majors', $this->dataForView);
    }

    public function grades(SchoolRequest $request){
        $dao = new GradeDao($request->user());
        $this->dataForView['grades'] = $dao->getBySchool(session('school.id'));
        return view('school_manager.school.grades', $this->dataForView);
    }

    public function teachers(SchoolRequest $request){
        $dao = new GradeUserDao($request->user());
        $this->dataForView['employees'] = $dao->getBySchool(session('school.id'), Role::GetTeacherUserTypes());
        return view('teacher.users.teachers', $this->dataForView);
    }

    public function students(SchoolRequest $request){
        $dao = new GradeUserDao($request->user());
        $this->dataForView['students'] = $dao->getBySchool(session('school.id'),Role::GetStudentUserTypes());
        return view('teacher.users.students', $this->dataForView);
    }
}
