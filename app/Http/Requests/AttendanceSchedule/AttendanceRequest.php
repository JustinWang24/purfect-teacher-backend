<?php


namespace App\Http\Requests\AttendanceSchedule;


use App\Http\Requests\MyStandardRequest;

class AttendanceRequest extends MyStandardRequest
{

    /**
     * 获取旷课数据
     * @return mixed
     */
    public function getTruantData() {
        return $this->get('truant');
    }


    public function getAttendanceId() {
        return $this->get('attendance_id');
    }
}
