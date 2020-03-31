<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 上午11:52
 */

namespace App\BusinessLogic\Attendances\Impl;


use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Models\AttendanceSchedules\Attendance as AttendanceModel;

class Truant
{

    public function saveData(AttendanceModel $attendance, AttendancesDetail $attendancesDetail) {

        if($attendancesDetail->mold != AttendancesDetail::MOLD_TRUANT) {
            // 更新详情状态
            $save = ['mold'=>AttendancesDetail::MOLD_TRUANT];
            $attendancesDetail->update($save);

            // 更新主表数据
            if($attendancesDetail->mold == AttendancesDetail::MOLD_SIGN_IN) {
                $field = 'actual_number'; // 签到人数
            } else {
                $field = 'leave_number';  // 请假人数
            }
            $attendance->increment('missing_number'); //旷课人数 +1
            $attendance->decrement($field); // 请假或签到人数 —1

        }
    }
}