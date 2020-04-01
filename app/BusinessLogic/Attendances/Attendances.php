<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 上午11:09
 */

namespace App\BusinessLogic\Attendances;


use App\User;
use Illuminate\Support\Facades\DB;
use App\Dao\Students\StudentLeaveDao;
use App\Models\Timetable\TimetableItem;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Models\AttendanceSchedules\Attendance as AttendanceModel;


class Attendances
{


    /**
     * 获取签到主表信息
     * @param TimetableItem $timetableItem
     * @param $week
     * @param User|null $currentUser
     * @param null $type
     * @return mixed
     * @throws \Exception
     */
    public static function getAttendance(TimetableItem $timetableItem, $week, User  $currentUser = null,$type = null) {
        $map = ['timetable_id'=>$timetableItem->id, 'week'=>$week];
        $attendance = AttendanceModel::where($map)->first();
        try{
            DB::beginTransaction();
            if(is_null($attendance)) {
                $attendanceDao = new AttendancesDao();
                $attendance = $attendanceDao->createAttendanceData($timetableItem);
            }

            // 初始化签到详情表
            self::createAttendancesDetail($attendance,$currentUser, $type);
            DB::commit();
            return $attendance;
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            throw new \Exception($msg);
        }

    }



    /**
     * 初始化签到详情表
     * @param $attendance
     * @param $currentUser
     * @param $type
     */
    private static function createAttendancesDetail($attendance,$currentUser, $type) {
        $gradeUser = $attendance->grade->gradeUser;
        $studentLeaveDao = new StudentLeaveDao();
        foreach ($gradeUser as $key => $val) {
            $detailInfo = $attendance->details->where('student_id',$val->user_id)->first();

            $leave = $studentLeaveDao->getStudentLeaveByTime($val->user_id);

            // 签到状态
            $mold = AttendancesDetail::MOLD_TRUANT;  // 旷课
            if(!is_null($leave)) {
                $mold = AttendancesDetail::MOLD_LEAVE; // 请假
            }
            if(empty($detailInfo)) {
                // 判断当前学生有没有请假
                $details = [
                    'attendance_id' => $attendance->id,
                    'course_id' => $attendance->course_id,
                    'timetable_id' => $attendance->timetable_id,
                    'student_id' => $val->user_id,
                    'year'=> $attendance->year,
                    'term' => $attendance->term,
                    'week'=>$attendance->week,
                    'mold'=> $mold,
                    'weekday_index'=>$attendance->timeTable->weekday_index,
                ];
                // 添加签到默认初始数据
                AttendancesDetail::create($details);
            } else {
                $logic = Factory::GetStepLogic($leave, $currentUser,$val->user_id);
                if(!is_null($logic)) {
                    $logic->saveData($attendance, $detailInfo, $type);
                }
            }

        }


    }

}