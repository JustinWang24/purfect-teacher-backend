<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 下午5:05
 */

namespace App\Http\Controllers\Operator\Evaluate;


use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluate\EvaluateRequest;

class EvaluationScoreController extends Controller
{
    public function index(EvaluateRequest $request) {
        $schoolId = $request->getSchoolId();
        $attendancesDao = new AttendancesDao();
        $return = $attendancesDao->getAttendanceBySchoolId($schoolId);
        $this->dataForView['list'] = $return;
        return view('school_manager.evaluate.score.list',$this->dataForView);
    }

}