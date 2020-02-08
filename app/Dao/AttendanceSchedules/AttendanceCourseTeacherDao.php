<?php


namespace App\Dao\AttendanceSchedules;



use App\Dao\Schools\SchoolDao;
use App\Models\AttendanceSchedules\AttendanceCourseTeacher;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;

class AttendanceCourseTeacherDao
{

    /**
     * @param User $user
     * @return bool|null
     */
    public function getAttendanceCourseTeacherByUser(User $user)
    {
        dd($user->gradeUser->grade_id);
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
        $now = Carbon::parse('2020-01-18 14:40:00');
        $school = (new SchoolDao())->getSchoolById($user->getSchoolId());
        $currentTimeSlot = GradeAndYearUtil::GetTimeSlot($now, $school->id);
        if($currentTimeSlot && $school){
            $weekdayIndex = $now->weekday();
            // 当前学年
            $year = $school->configuration->getSchoolYear();

            $term = $school->configuration->guessTerm($now->month);
            $where = [
                ['school_id','=',$school->id],
                ['year','=',$year],
                ['term','=',$term],
                ['grade_id','=',$user->gradeUser->grade_id],
                ['weekday_index','=',$weekdayIndex],
                ['teacher_id', '=', $user->id],
            ];
            return AttendanceCourseTeacher::where($where)->first();
        }
        return null;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
//        return AttendanceCourseTeacher::create();
    }

}
