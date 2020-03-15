<?php

namespace App\Http\Controllers\Operator\TeacherAttendance;

use App\Dao\Schools\OrganizationDao;
use App\Dao\TeacherAttendance\AttendanceDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherAttendance\AttendanceRequest;
use App\Models\Schools\Organization;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function manager(AttendanceRequest $request){
        $this->dataForView['pageTitle'] = '考勤管理';
        $dao = new AttendanceDao();
        $this->dataForView['attendances'] = $dao->getPaginated($request->session()->get('school.id'));
        return view('school_manager.teacher_attendance.manager', $this->dataForView);
    }

    public function load_manager(AttendanceRequest $request){
        $dao = new AttendanceDao();
        $result = $dao->getPaginated($request->session()->get('school.id'));
        return JsonBuilder::Success($result);
    }

    public function load_attendance(AttendanceRequest $request) {
        $dao = new AttendanceDao();
        $info = $dao->getById($request->get('attendance_id'));
        $organizationArr = [];
        $organizationDao = new OrganizationDao();
        foreach ($info->organizations as $organization) {
            $nowLevel = $organization->level;
            $return = [$organization->id];
            $parentid = $organization->parent_id;
            while ($nowLevel > 1) {
                $parent = $organizationDao->getById($parentid);
                array_unshift($return, $parent->id);
                $parentid = $parent->parent_id;
                $nowLevel = $parent->level;
            }
            $organizationArr[] = $return;
        }
        unset($info->organizations);
        $info->organizations = $organizationArr;

        $managerArr = [];
        if (!empty($info->managers)) {
            foreach ($info->managers as $manager) {
                $managerArr[] = [
                    'id' => $manager->user->id,
                    'name' => $manager->user->name
                ];
            }
        }
        unset($info->managers);
        $info->managers = $managerArr;
        return JsonBuilder::Success($info);
    }

    public function save_attendance(AttendanceRequest $request) {
        $dao = new AttendanceDao();
        $attendance = $request->getAttendanceData();
        $organizations = $request->getOrganizationsData();
        $managers = $request->getMenagersData();
        if(empty($attendance['id'])){
            // 创建
            $result = $dao->create($attendance, $organizations, $managers);
            return $result->isSuccess() ?
                JsonBuilder::Success(['id'=>$result->getData()->id]) :
                JsonBuilder::Error($result->getMessage());
        }else {
            //更新
            $result = $dao->update($attendance, $organizations, $managers);
            return $result->isSuccess() ?
                JsonBuilder::Success(['id'=>$result->getData()->id]) :
                JsonBuilder::Error($result->getMessage());
        }
    }

    public function save_clocksets(AttendanceRequest $request) {
        $dao = new AttendanceDao();
        $attendance = $dao->getById($request->get('attendance_id'));
        if (!$attendance) {
            return JsonBuilder::error('数据有误');
        }
        $clockSets = $request->getClockSetsData();
        $result = $dao->saveClocksets($attendance, $clockSets);
        return $result->isSuccess() ?
            JsonBuilder::Success() :
            JsonBuilder::Error($result->getMessage());
    }

    public function delete_exceptionday(AttendanceRequest $request){
        $dao = new AttendanceDao();
        return JsonBuilder::Success($dao->deleteExceptionday($request->get('id')));
    }
    public function save_exceptionday(AttendanceRequest $request) {
        $dao = new AttendanceDao();
        $day = Carbon::parse($request->getInputDay());
        $attendanceId = $request->get('attendance_id');
        if ($check = $dao->getExceptiondayByday($attendanceId, $day)) {
            return JsonBuilder::Success($check);
        }
        return JsonBuilder::Success($dao->saveExceptionday($attendanceId, $day));
    }
}
