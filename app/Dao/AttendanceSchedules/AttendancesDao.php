<?php

namespace App\Dao\AttendanceSchedules;


use App\Dao\Schools\SchoolDao;
use App\Dao\Users\UserDao;
use App\Models\AttendanceSchedules\Attendance;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Models\Timetable\TimetableItem;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendancesDao
{

    /**
     * @param $year
     * @param $timetableId
     * @param $term
     * @param $week
     * @return mixed
     */
    public function getAttendanceByTimeTableId($year, $timetableId, $term, $week)
    {
        $map = ['year'=>$year, 'timetable_id'=>$timetableId, 'term'=>$term, 'week'=>$week];
        return Attendance::where($map)->first();
    }

    /**
     * 签到
     * @param $item TimetableItem
     * @param $user User
     * @param $type
     * @return bool
     */
    public function arrive($item, $user, $type)
    {

        $attendance = Attendance::where('timetable_id', $item->id)->first();

        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
        $schoolDao = new SchoolDao;
        $school = $schoolDao->getSchoolById($item->school_id);
        $week = $school->configuration->getScheduleWeek($now)->getScheduleWeekIndex();
        DB::beginTransaction();
        try{
            if(empty($attendance)) {
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
                ];
                $attendance = Attendance::create($attendanceData);
            }
            $data = [
                'attendance_id' => $attendance->id,
                'student_id'    => $user->id,
                'timetable_id'  => $item->id,
                'course_id'     => $item->course_id,
                'type'          => $type,
                'year'          => $item->year,
                'term'          => $item->term,
                'week'          => $week,
                'weekday_index' => $item->weekday_index,
                'mold'          => AttendancesDetail::MOLD_SIGN_IN,
                'date'          => Carbon::now()
            ];

            $detailsDao = new  AttendancesDetailsDao;
            $detailsDao->add($data);

            Attendance::where('id', $attendance->id)->decrement('missing_number'); // 未到人数 -1
            Attendance::where('id', $attendance->id)->increment('actual_number');  // 实到人数 +1
            DB::commit();
            $result = true;
        }catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }

        return $result;

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

}
