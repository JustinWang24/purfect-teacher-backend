<?php

namespace App\Dao\AttendanceSchedules;


use App\Models\AttendanceSchedules\Attendance;
use Illuminate\Support\Facades\DB;

class AttendancesDao
{

    /**
     * @param $id
     * @return mixed
     */
    public function getAttendanceByTimeTableId($id)
    {
        return Attendance::where('timetable_id', $id)->first();
    }

    /**
     * 签到
     * @param $item
     * @param $student
     */
    public function arrive($item, $student)
    {

        $attendance = Attendance::where('timetable_id', $item->id)->first();

        if (empty($attendance)) {

            $gradeUser = $item->grade->gradeUser;
            $userIds   = $gradeUser->pluck('user_id');
            $attendanceData = [
                'timetable_id'   => $item->id,
                'actual_number'  => 0,
                'leave_number'   => 0, // todo :: 请假总人数 创建请假表
                'missing_number' => count($userIds),
                'total_number'   => count($userIds),
                'year'           => $item->year,
                'term'           => $item->term,
                'grade_id'       => $item->grade_id,
                'teacher_id'     => $item->teacher_id,
                'week'           =>
            ];
            Attendance::create($attendanceData);
        } else {

            DB::beginTransaction();
            try{
                $data = [
                    'attendance_id' => $attendance->id,
                    'student_id'    => $student->user_id,
                    'timetable_id'  => $item->id,
                    'course_id'     => $item->course_id,
                ];
                AttendancesDetailsDao::add($data);


                DB::commit();
            }catch (\Exception $e) {
                DB::rollBack();
            }






        }





    }

}
