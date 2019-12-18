<?php

namespace App\Dao\AttendanceSchedules;


use App\Models\AttendanceSchedules\AttendancesDetail;

class AttendancesDetailsDao
{

    /**
     * 获取签到详情
     * @param $timeTableId
     * @param $studentId
     * @return AttendancesDetail
     */
    public function getDetailByTimeTableIdAndStudentId($timeTableId, $studentId)
    {
        return AttendancesDetail::where('timetable_id', $timeTableId)
                ->where('student_id', $studentId)
                ->first();
    }


    public function add($data)
    {
        return AttendancesDetail::create($data);
    }

}
