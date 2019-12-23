<?php


namespace App\Http\Controllers\Operator\OA;


use App\Dao\OA\AttendanceTeacherDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AttendanceTeacherController extends Controller
{
    public function management(Request $request){
        $dao = new AttendanceTeacherDao();
        $this->dataForView['pageTitle'] = '办公/考勤管理';
        $this->dataForView['projects'] = $dao->getAttendanceGroups($request->getSchoolId());
        return view('school_manager.oa.attendance',$this->dataForView);
    }
}
