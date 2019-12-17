<?php

namespace App\Dao\AttendanceSchedules;


use App\Models\AttendanceSchedules\Attendance;

class AttendancesDao
{

    /**
     * @param $id
     * @return mixed
     */
    public function getAttendanceById($id)
    {
        return Attendance::find($id);
    }


}
