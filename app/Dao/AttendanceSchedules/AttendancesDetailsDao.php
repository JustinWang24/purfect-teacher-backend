<?php

namespace App\Dao\AttendanceSchedules;


use App\Dao\Schools\SchoolDao;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;

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
        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($item->school_id);
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
        $week = $school->configuration->getScheduleWeek($now)->getScheduleWeekIndex();
        $where = [
            ['timetable_id','=',$item->id],
            ['year','=', $item->year],
            ['term','=',$item->term],
            ['student_id','=',$user->id],
            ['weekday_index','=', $item->weekday_index],
            ['week' ,'=', $week],
            ['mold', '=', AttendancesDetail::MOLD_SIGN_IN]
        ];
        return AttendancesDetail::where($where)->first();
    }

    /**
     * 签到详情添加
     * @param $data
     * @return mixed
     */
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
        $field = ['timetable_id', 'date', 'mold', 'status'];
        $map = ['year'=>$year, 'student_id'=>$userId, 'course_id'=>$courseId, 'term'=>$term];
        return AttendancesDetail::where($map)
            ->orderBy('created_at')
            ->select($field)
            ->get();
    }

    /**
     * 查寻旷课
     * @param $userId
     * @param $date
     * @param $timetableId
     * @return mixed
     */
    public function getTruantDetailByUserId($userId,$date,$timetableId) {
        $map = ['student_id'=>$userId, 'date'=>$date, 'timetable_id'=>$timetableId];
        return AttendancesDetail::where($map)->first();
    }

    /**
     * @param $attendanceId
     * @return mixed
     */
    public function getAttendDetailsByAttendanceId($attendanceId) {
        return AttendancesDetail::where('attendance_id', $attendanceId)->get();
    }


}
