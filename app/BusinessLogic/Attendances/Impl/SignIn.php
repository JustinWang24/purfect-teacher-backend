<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 上午11:25
 */

namespace App\BusinessLogic\Attendances\Impl;

use Carbon\Carbon;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Models\AttendanceSchedules\Attendance as AttendanceModel;

class SignIn
{

    public function saveData(AttendanceModel $attendance, AttendancesDetail $attendancesDetail, $type) {
        // 当前状态不等于签到
        if($attendancesDetail->mold != AttendancesDetail::MOLD_SIGN_IN) {

            // 更新主表状态
            if($attendancesDetail->mold == AttendancesDetail::MOLD_LEAVE) {
                $field = 'leave_number'; // 请假人数
            } else {
                $field = 'missing_number'; // 旷课人数
            }

            $save = [
                'mold'=>AttendancesDetail::MOLD_SIGN_IN,
                'type'=>$type,
                'signin_time' => Carbon::now(),
                ];
            // 更新详情表状态
            $attendancesDetail->update($save);

            $attendance->increment('actual_number'); //签到人数 +1
            $attendance->decrement($field); // 请假或旷课人数 —1
        }

    }
}