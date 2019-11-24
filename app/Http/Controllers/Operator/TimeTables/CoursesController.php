<?php

namespace App\Http\Controllers\Operator\TimeTables;

use App\Http\Requests\School\CourseRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;

class CoursesController extends Controller
{
    public function manager(CourseRequest $request){
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());
        $this->dataForView['pageTitle'] = $school->name . ' 课程管理';
        $this->dataForView['needManagerNav'] = false;
        $this->dataForView['school'] = $school;
        $this->dataForView['app_name'] = 'courses_mgr_app';
        return view('school_manager.timetable.manager', $this->dataForView);
    }
}
