<?php


namespace App\Dao\OA;


use App\Models\OA\AttendanceTeacher;
use App\Models\OA\AttendanceTeacherGroup;
use App\Models\OA\AttendanceTeachersGroupMember;
use App\Models\OA\AttendanceTeachersMessage;
use Carbon\Carbon;

class AttendanceTeacherDao
{
    public function __construct()
    {
    }

    public function createAttendanceTeacher($data)
    {
        return AttendanceTeacher::create($data);
    }

    public function getTodayRecord($userId,$schoolId)
    {
        return AttendanceTeacher::where('user_id',$userId)
            ->where('school_id', $schoolId)
            ->where('check_in_date', date('Y-m-d'))
            ->first();
    }
    public function updateAttendanceTeacher($userId,$schoolId,$data)
    {
        return AttendanceTeacher::where('user_id',$userId)
            ->where('check_in_date', date('Y-m-d'))
            ->where('school_id', $schoolId)
            ->update($data);
    }
    public function getGroupInfo($userId,$schoolId)
    {
        $memberObj = AttendanceTeachersGroupMember::where('user_id', $userId)
            ->where('school_id',$schoolId)
            ->first();
        if ($memberObj) {
            return $memberObj->group;
        } else{
            return false;
        }

    }


    public function getRecord($userId,$schoolId,$day)
    {
        return AttendanceTeacher::where('user_id',$userId)
            ->where('school_id', $schoolId)
            ->where('check_in_date', date('Y-m-d',strtotime($day)))
            ->first();
    }

    /**
     * 上班打卡结果 0-未打卡 1-正常打卡 2-迟到 3-严重迟到 5-缺卡
     * 下班打卡结果 0-未打卡 1-正常打卡 2-早退 5-缺卡
     * 打卡按钮 0-不在打卡时间 1-上班打卡 2-迟到 3-严重迟到 4-下班打卡 5-早退打卡 6-已打完卡
     */
    public function getStatus($record)
    {
        $group = $record->member->group;
        $result = [];
        //上班状态
        if (empty($record->online_mine)) {
            $result['online_status'] = AttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->online_mine) <= strtotime($record->check_in_date.' '.$group->online_time)) {
            $result['online_status'] = AttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->online_mine) > strtotime($record->check_in_date.' '.$group->online_time)) {
            $diff = strtotime($record->online_mine)-strtotime($record->check_in_date.' '.$group->online_time);
            if ($diff > $group->late_duration*60) {
                $result['online_status'] = AttendanceTeacherGroup::SERIOUSLATE;
            } elseif($diff <= $group->late_duration*60) {
                $result['online_status'] = AttendanceTeacherGroup::BELATE;
            }
        }

        //下班状态
        if (empty($record->offline_mine)) {
            $result['offline_status'] = AttendanceTeacherGroup::LOST;
        } elseif (strtotime($record->offline_mine) >= strtotime($record->check_in_date.' '.$group->offline_time)) {
            $result['offline_status'] = AttendanceTeacherGroup::CHECKED;
        } elseif (strtotime($record->offline_mine) < strtotime($record->check_in_date.' '.$group->offline_time)) {
            $result['offline_status'] = AttendanceTeacherGroup::LEAVEEARLY;
        }

        //按钮状态
        $time = time();
        if (date('N',$time)>5) {
            $result['button_status'] = AttendanceTeacherGroup::BTNNULL;
        } elseif($time < strtotime($group->online_time) && empty($record->online_mine)) {
            $result['button_status'] = AttendanceTeacherGroup::CHECKED;
        } elseif($time > strtotime($group->offline_time) && empty($record->offline_mine)) {
            $result['button_status'] = AttendanceTeacherGroup::OFFWORK;
        } elseif(empty($record->online_mine) && $time > strtotime($group->online_time)) {
            $diff = $time-strtotime($group->online_time);
            if ($diff > $group->serious_late_duration*60) {
                $result['button_status'] = AttendanceTeacherGroup::SERIOUSLATE;
            } elseif($diff <= $group->late_duration*60) {
                $result['button_status'] = AttendanceTeacherGroup::BELATE;
            }
        } elseif(empty($record->offline_mine) && $time > strtotime($group->online_time)) {
            if ($time < strtotime($group->offline_time)) {
                $result['button_status'] = AttendanceTeacherGroup::BTNLEAVEEARLY;
            } elseif($time > strtotime($group->offline_time)) {
                $result['button_status'] = AttendanceTeacherGroup::OFFWORK;
            }
        } elseif(!empty($record->online_mine) && !empty($record->offline_mine)) {
            $result['button_status'] = AttendanceTeacherGroup::BTNFINISH;
        }
            return $result;
    }
    //获取某个月的打卡记录
    public function getMonthList($userId, $month='')
    {
        $timeArr = $this->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $row = AttendanceTeacher::where('user_id',$userId)
            ->where('check_in_date','>=',$start)
            ->where('check_in_date','<',$end)
            ->get();
        $output = [];
        foreach($row as $key=>$value) {
            $output[$value->check_in_date] = $value;
        }
        return $output;
    }
    public function getStartAndEndArr($month)
    {
        if(empty($month)) {
            $start = date('Y-m-d', strtotime('first day of this month'));
            $end  = date('Y-m-d', strtotime('first day of next month'));
        } else {
            $start = date('Y-m-d', strtotime(strval($month)."-01"));
            $end  = date('Y-m-d', strtotime('+1 month', strtotime($start)));
        }
        return ['start'=>$start,'end'=>$end];
    }
    //查询补卡记录
    public function getMessageRecord($userId,$date)
    {
        return AttendanceTeachersMessage::where('user_id',$userId)
            ->where('attendance_date',$date)
            ->first();
    }
    //根据补卡记录的操作情况判断补卡状态
    public function getMessageOptStatus($userId,$date)
    {
        $message = $this->getMessageRecord($userId, $date);
        if (empty($message)) {
            //没有申请记录没有考勤记录说明未补卡
            $status = AttendanceTeachersMessage::NOTOPT;
        } else {
            //有补卡记录但是考勤表没有记录说明被拒绝
            if ($message->status == 2) {
                $status = AttendanceTeachersMessage::CANCLE;
            } else {
                //有补卡记录但是补卡记录没有完成说明正在补卡中
                $status = AttendanceTeachersMessage::DOINGOPT;
            }
        }
        return $status;
    }
}
