<?php

namespace App\Dao\AttendanceSchedules;


use App\Models\AttendanceSchedules\AttendancesDetail;

class AttendancesDetailsDao
{

    /**
     * 统计签到次数
     * @param $userId
     * @param $courseId
     * @param $year
     * @param $term
     * @return mixed
     */
    public function getDetailsCountByUser($userId, $courseId, $year, $term) {
        $map = ['student_id'=>$userId, 'course_id'=>$courseId, 'year'=>$year, 'term'=>$term];
        return AttendancesDetail::where($map)->count();
    }


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
