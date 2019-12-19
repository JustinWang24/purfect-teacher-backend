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

    /**
     * 获取签到详情
     * @param $item
     * @param $user
     * @return AttendancesDetail
     */
    public function getDetailByTimeTableIdAndStudentId($item, $user)
    {
        $where = [
            ['timetable_id','=',$item->id],
            ['year','=', $item->year],
            ['term','=',$item->term],
            ['weekday_index','=',$item->week],
            ['student_id','=',$user->id],
        ];
        return AttendancesDetail::where($where)->first();
    }


    public function add($data)
    {
        return AttendancesDetail::create($data);
    }

}
