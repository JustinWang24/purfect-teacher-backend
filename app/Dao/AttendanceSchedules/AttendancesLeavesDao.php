<?php


namespace App\Dao\AttendanceSchedules;


use App\Models\AttendanceSchedules\AttendancesLeaves;

class AttendancesLeavesDao
{

    /**
     * 请假次数
     * @param $userId
     * @param $courseId
     * @param $year
     * @param $term
     * @return mixed
     */
    public function getLeavesCountByUser($userId, $courseId, $year, $term) {
        $map = ['user_id'=>$userId, 'course_id'=>$courseId, 'year'=>$year,
            'term'=>$term, 'status'=>AttendancesLeaves::STATUS_CONSENT];
        return AttendancesLeaves::where($map)->count();
    }
}
