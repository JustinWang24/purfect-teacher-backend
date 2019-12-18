<?php

namespace App\Dao\AttendanceSchedules;


use App\Models\AttendanceSchedules\Attendance;

class AttendancesDao
{

    /**
     * @param $id
     * @return mixed
     */
    public function getAttendanceByTimeTableId($id)
    {
        return Attendance::where('timetable_id', $id)->first();
    }

    /**
     * 签到
     */
    public function arrive()
    {

    }



}
