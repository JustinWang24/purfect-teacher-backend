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
    public function getSignInCountByUser($userId, $courseId, $year, $term) {
        $map = ['student_id'=>$userId, 'course_id'=>$courseId, 'year'=>$year,
            'term'=>$term, 'mold'=>AttendancesDetail::MOLD_SIGN_IN];
        return AttendancesDetail::where($map)->count();
    }


    /**
     * 统计请假次数
     * @param $userId
     * @param $courseId
     * @param $year
     * @param $term
     * @return mixed
     */
    public function getLeaveCountByUser($userId, $courseId, $year, $term) {
        $map = ['student_id'=>$userId, 'course_id'=>$courseId, 'year'=>$year, 'term'=>$term,
            'mold'=>AttendancesDetail::MOLD_LEAVE, 'status'=>AttendancesDetail::STATUS_CONSENT];
        return AttendancesDetail::where($map)->count();
    }

    /**
     * 统计旷课次数
     * @param $userId
     * @param $courseId
     * @param $year
     * @param $term
     * @return mixed
     */
    public function getTruantCountByUser($userId, $courseId, $year, $term) {
        $map = ['student_id'=>$userId, 'course_id'=>$courseId, 'year'=>$year,
            'term'=>$term, 'mold'=>AttendancesDetail::MOLD_TRUANT];
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


    /**
     * 课程签到列表
     * @param $year
     * @param $userId
     * @param $courseId
     * @param $term
     * @return mixed
     */
    public function signInList($year, $userId, $courseId, $term) {
        $field = ['timetable_id', 'created_at', 'mold', 'status'];
        $map = ['year'=>$year, 'student_id'=>$userId, 'course_id'=>$courseId, 'term'=>$term];
        return AttendancesDetail::where($map)
            ->orderBy('created_at')
            ->select($field)
            ->get();
    }




}
