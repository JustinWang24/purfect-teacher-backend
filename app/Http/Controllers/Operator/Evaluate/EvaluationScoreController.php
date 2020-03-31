<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 下午5:05
 */

namespace App\Http\Controllers\Operator\Evaluate;


use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluate\EvaluateRequest;

class EvaluationScoreController extends Controller
{

    /**
     * 课节评分列表
     * @param EvaluateRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(EvaluateRequest $request) {
        $schoolId = $request->getSchoolId();
        $attendancesDao = new AttendancesDao();
        $return = $attendancesDao->getAttendanceBySchoolId($schoolId);
        $this->dataForView['list'] = $return;
        return view('school_manager.evaluate.score.list',$this->dataForView);
    }


    /**
     * 评分详情列表
     * @param EvaluateRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function details(EvaluateRequest $request) {
        $attendanceId = $request->getAttendanceId();
        $detailsDao = new AttendancesDetailsDao();
        $result = $detailsDao->getDetailsPageByAttendanceId($attendanceId);
        $this->dataForView['list'] = $result;
        return view('school_manager.evaluate.score.details',$this->dataForView);
    }

}