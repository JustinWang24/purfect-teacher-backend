<?php

namespace App\Dao\AttendanceSchedules;


use App\User;
use Carbon\Carbon;
use App\Dao\Schools\SchoolDao;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\Time\GradeAndYearUtil;
use App\Models\Timetable\TimetableItem;
use App\BusinessLogic\Attendances\Attendances;
use App\Models\AttendanceSchedules\Attendance;

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

        $attendance = $this->isAttendanceByTimetableAndWeek($item,$week,$user,$type);
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
            'time_slot_id'   => $item->time_slot_id,
            'school_id'      => $item->school_id
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
     * @param TimetableItem $timetable
     * @param $week
     * @param User|null $user
     * @param null $type
     * @return mixed
     * @throws \Exception
     */
    public function isAttendanceByTimetableAndWeek(TimetableItem $timetable, $week,User $user = null, $type = null ) {
        return Attendances::getAttendance($timetable, $week, $user,$type);
    }


    /**
     * 查询签到评分
     * @param $schoolId
     * @return mixed
     */
    public function getAttendanceBySchoolId($schoolId) {
        return Attendance::where('school_id', $schoolId)
            ->where('status',Attendance::STATUS_EVALUATE)
            ->orderBy('id','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


}
