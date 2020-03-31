<?php

namespace App\Dao\AttendanceSchedules;


use App\BusinessLogic\Attendances\Attendances;
use App\Dao\Students\StudentLeaveDao;
use App\User;
use Carbon\Carbon;
use App\Dao\Schools\SchoolDao;
use Illuminate\Support\Facades\DB;
use App\Utils\Time\GradeAndYearUtil;
use App\Models\Timetable\TimetableItem;
use App\Models\AttendanceSchedules\Attendance;
use App\Models\AttendanceSchedules\AttendancesDetail;

class AttendancesDao
{

    /**
     * @param $timetableId
     * @param $week
     * @return mixed
     */
    public function getAttendanceByTimeTableId($timetableId, $week)
    {
        $map = ['timetable_id'=>$timetableId, 'week'=>$week];
        return Attendance::where($map)->first();
    }

    /**
     * 签到
     * @param $item TimetableItem
     * @param $user User
     * @param $type
     * @return bool
     * @throws \Exception
     */
    public function arrive($item, $user, $type)
    {

        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($user->getSchoolId());
        $configuration = $school->configuration;
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
        $month = Carbon::parse($now)->month;
        $term = $configuration->guessTerm($month);
        $weeks = $configuration->getScheduleWeek($now, null, $term);
        $week = $weeks->getScheduleWeekIndex();

        $attendance = $this->isAttendanceByTimetableAndWeek($item,$week,$type);
        return $attendance;

    }


    /**
     * @param $date
     * @param $gradeId
     * @param $teacherId
     * @return mixed
     */
    public function getAttendByDateTimeAndGradeIdAndTeacherId($date, $gradeId, $teacherId) {
        $map = ['teacher_id'=>$teacherId, 'grade_id'=>$gradeId];
        return Attendance::where($map)->whereDate('created_at', $date)->get();
    }

    /**
     * 根据学年和学期获取老师待的课程
     * @param $userId
     * @param $year
     * @param $term
     * @return mixed
     */
    public function getSignInCoursesByYearAndTerm($userId, $year, $term){
        $map = ['teacher_id'=>$userId, 'year'=>$year, 'term'=>$term];
        return Attendance::where($map)
            ->select('course_id')
            ->distinct('course_id')
            ->get();
    }



    /**
     * 根据课程ID，学年学期获取老师带的班级
     * @param $userId
     * @param $courseId
     * @param $year
     * @param $term
     * @return mixed
     */
    public function getSignInGradesByCourseIdAndYearTerm($userId, $courseId, $year, $term) {
        $map = ['teacher_id'=>$userId, 'course_id'=>$courseId, 'year'=>$year, 'term'=>$term];
        return Attendance::where($map)
            ->select('grade_id')
            ->distinct('grade_id')
            ->get();
    }



    public function getAttendanceById($attendanceId) {
        return Attendance::find($attendanceId);
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return Attendance::where('id', $id)->update($data);
    }

    /**
     * 查询老师是否签到
     * @param TimetableItem $item
     * @param User $user
     * @return mixed
     */
    public function getTeacherIsSignByItem(TimetableItem $item, $user)
    {
        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($user->getSchoolId());
        $configuration = $school->configuration;
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);

        $month = Carbon::parse($now)->month;
        $term = $configuration->guessTerm($month);
        $weeks = $configuration->getScheduleWeek($now, null, $term);
        $week = $weeks->getScheduleWeekIndex();

        $where = [
           'timetable_id' => $item->id,
           'week' => $week,
        ];
        return Attendance::where($where)->first();
    }

    /**
     * 教师签到
     * @param $id
     * @param $late
     * @return mixed
     */
    public function updateTeacherSignByItem($id, $late)
    {
        $data = [
            'teacher_sign' => Attendance::TEACHER_SIGN,
            'teacher_sign_time' => Carbon::now(),
            'teacher_late' => $late,
        ];

        return Attendance::where('id', $id)->update($data);
    }

    /**
     * 创建签到总表数据
     * @param TimetableItem $item
     * @return mixed
     */
    public function createAttendanceData(TimetableItem $item)
    {
        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($item->school_id);
        $configuration = $school->configuration;
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);

        $month = Carbon::parse($now)->month;
        $term = $configuration->guessTerm($month);
        $weeks = $configuration->getScheduleWeek($now, null, $term);
        $week = $weeks->getScheduleWeekIndex();

        $gradeUser = $item->grade->gradeUser;
        $userIds   = $gradeUser->pluck('user_id');
        $attendanceData = [
            'timetable_id'   => $item->id,
            'course_id'      => $item->course_id,
            'actual_number'  => 0,
            'leave_number'   => 0, // todo :: 请假总人数 创建请假表
            'missing_number' => count($userIds),
            'total_number'   => count($userIds),
            'year'           => $item->year,
            'term'           => $item->term,
            'grade_id'       => $item->grade_id,
            'teacher_id'     => $item->teacher_id,
            'week'           => $week,
            'time_slot_id'   => $item->time_slot_id
        ];

        return Attendance::create($attendanceData);
    }


    /**
     * 统计老师签到
     * @param $timetableIds
     * @param $week
     * @param $signStatus
     * @param $late
     * @return mixed
     */
    public function getTeacherSignInStatus($timetableIds, $week, $signStatus, $late = false)
    {
        $map = [
            ['week', '=' ,$week],
            ['teacher_sign', '=', $signStatus]
        ];
        if ($late) {
           array_push($map, ['teacher_late', '=', $late]);
        }
        return Attendance::where($map)->whereIn('timetable_id', $timetableIds)->count();
    }


  /**
   * 根据 给定时间获取所有签到
   * @param $time
   * @param $timeSlotId
   * @return mixed
   */
    public function getTeacherSignInfoByTime($time, $timeSlotId = false)
    {
        $result =  Attendance::whereDate('created_at', $time);

        if ($timeSlotId) {
            $result->where('time_slot_id', $timeSlotId);
        }

        return $result->get();
    }

    /**
     * 统计老师签到
     * @param $timetableIds
     * @param $week
     * @param $signStatus
     * @param $late
     * @return mixed
     */
    public function getTeacherSignInfo($timetableIds, $week, $signStatus, $late = false)
    {
        $map = [
            ['week', '=' ,$week],
            ['teacher_sign', '=', $signStatus]
        ];
        if ($late) {
           array_push($map, ['teacher_late', '=', $late]);
        }
        return Attendance::where($map)->whereIn('timetable_id', $timetableIds)->get();
    }

    /**
     * 判断是否有签到主表
     * @param TimetableItem $timetable
     * @param $week
     * @param null $type 签到类型
     * @return mixed
     * @throws \Exception
     */
    public function isAttendanceByTimetableAndWeek(TimetableItem $timetable, $week,$type = null ) {
        return Attendances::getAttendance($timetable, $week, $type);
//        $map = ['timetable_id'=>$timetable->id, 'week'=>$week];
//        $attendance = Attendance::where($map)->first();
//        if(is_null($attendance)) {
//            // 创建签到总表
//            $attendance = $this->createAttendanceData($timetable);
//        }
//        $gradeUser = $attendance->grade->gradeUser;
//
//        $detailsDao = new AttendancesDetailsDao();
//        $studentLeaveDao = new StudentLeaveDao();
//        foreach ($gradeUser as $key => $val) {
//            $detailInfo = $attendance->details->where('student_id',$val->user_id)->first();
//            $leave = $studentLeaveDao->getStudentLeaveByTime($val->user_id);
//            // 签到状态
//            $mold = AttendancesDetail::MOLD_TRUANT;  // 旷课
//            if(!is_null($leave)) {
//                $mold = AttendancesDetail::MOLD_LEAVE; // 请假
//            }
//            // 为空再添加
//            if(is_null($detailInfo)) {
//                // 判断当前学生有没有请假
//                $details = [
//                    'attendance_id' => $attendance->id,
//                    'course_id' => $attendance->course_id,
//                    'timetable_id' => $attendance->timetable_id,
//                    'student_id' => $val->user_id,
//                    'year'=> $attendance->year,
//                    'term' => $attendance->term,
//                    'week'=>$attendance->week,
//                    'mold'=> $mold,
//                    'weekday_index'=>$attendance->timeTable->weekday_index,
//                ];
//                // 添加签到默认初始数据
//                $detailsDao->add($details);
//
//
//            // 判断当前学生签到记录是否是请假 && 当前学生是请假
//
//            } elseif($detailInfo->mold != AttendancesDetail::MOLD_LEAVE && $mold == AttendancesDetail::MOLD_LEAVE ) {
//                // 修改签到详情
//                $data = ['mold'=>AttendancesDetail::MOLD_LEAVE];
//                $detailsDao->update($detailInfo->id,$data);
//                // 修改签到总表
//                $map = ['id'=>$attendance->id];
//                Attendance::where($map)->increment('leave_number'); // 请假人数+1
//                // 判断签到或旷课 -1
//                if($detailInfo->mold == AttendancesDetail::MOLD_SIGN_IN) {
//                    $field = 'actual_number'; // 实到人数
//                } else {
//                    $field = 'missing_number'; // 未到人数
//                }
//                Attendance::where($map)->decrement($field); // -1
//            } else {
//                // 修改签到详情
//                $data = ['mold'=>AttendancesDetail::MOLD_SIGN_IN];
//                $detailsDao->update($detailInfo->id,$data);
//
//                // 修改签到总表
//                $map = ['id'=>$attendance->id];
//                Attendance::where($map)->increment('actual_number'); // 签到人数 +1
//                // 判断请假或旷课 -1
//                if($detailInfo->mold == AttendancesDetail::MOLD_LEAVE) {
//                    $field = 'leave_number'; // 请假人数
//                } else {
//                    $field = 'missing_number'; // 未到人数
//                }
//                Attendance::where($map)->decrement($field); // -1
//
//            }
//        }
//        return $attendance;
    }


}
