<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 上午11:34
 */

namespace App\BusinessLogic\Attendances\Impl;


use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Models\AttendanceSchedules\Attendance as AttendanceModel;

class Leave
{

    public function saveData(AttendanceModel $attendance, AttendancesDetail $attendancesDetail, $type) {

        // 当前状态不等于请假
        if($attendancesDetail->mold != AttendancesDetail::MOLD_LEAVE) {

            // 更新主表数据
            if($attendancesDetail->mold == AttendancesDetail::MOLD_SIGN_IN) {
                $field = 'actual_number';
            } else {
                $field = 'missing_number';
            }

            // 更新详情状态
            $save = ['mold'=>AttendancesDetail::MOLD_LEAVE];
            $attendancesDetail->update($save);


            $attendance->increment('leave_number'); //请假人数 +1
            $attendance->decrement($field); // 签到或旷课人数 —1

        }
    }
}