<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 3:19 PM
 */

namespace App\Http\Controllers\Operator\TimeTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeTable\TimetableRequest;
use App\Dao\Schools\SchoolDao;

class TimetablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function manager(TimetableRequest $request){
        $schoolDao = new SchoolDao($request->user());
        $school = $schoolDao->getSchoolByUuid($request->uuid());

        $this->dataForView['pageTitle'] = $school->name . ' 课程表管理';
        $this->dataForView['school'] = $school;
        return view('school_manager.timetable.manager', $this->dataForView);
    }
}