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

    // 查询总共的课时
}
