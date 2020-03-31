<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/30
 * Time: 下午8:02
 */

namespace App\Dao\Students;


use App\Models\Students\StudentLeave;
use Carbon\Carbon;

class StudentLeaveDao
{


    /**
     * 获取用户当前时间是否请假
     * @param $userId
     * @param $time
     * @return mixed
     */
    public function getStudentLeaveByTime($userId, $time = Null) {
        if(is_null($time)) {
            $time = Carbon::now();
        }
        $map = [
            ['user_id','=',$userId],
            ['start_time','<',$time],
            ['end_time','>',$time]
        ];
        return StudentLeave::where($map)->first();
    }
}