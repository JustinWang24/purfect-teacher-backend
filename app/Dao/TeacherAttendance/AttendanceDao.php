<?php

namespace App\Dao\TeacherAttendance;

use App\Models\TeacherAttendance\Attendance;
use Carbon\Carbon;

class AttendanceDao
{
    /**
     * 根据组织获取考勤基本配置
     * @param $organizationIdArr 组织id数组
     * @param $school_id 学校id
     * @return mixed
     */
    public function getByOrganizationIdArr($organizationIdArr, $school_id)
    {
        return Attendance::whereHas('organizations', function ($query) use ($organizationIdArr) {
            $query->whereIn('organizations.id', $organizationIdArr);
        })->where('school_id', $school_id)->first();
    }

    /**
     * 根据组织获取考勤配置列表
     * @param $organizationIdArr
     * @param $school_id
     * @return mixed
     */
    public function getListByOrganizationIdArr($organizationIdArr, $school_id)
    {
        return Attendance::whereHas('organizations', function ($query) use ($organizationIdArr) {
            $query->whereIn('organizations.id', $organizationIdArr);
        })->where('school_id', $school_id)->get();
    }

    /**
     * 根据日期获取当日考勤打卡配置
     * @param Attendance $attendance 考勤基本配置
     * @param $enday
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function getOnedayClockset(Attendance $attendance, $enday)
    {
        return $attendance->clocksets()->where('week', $enday)->first();
    }

    /**
     * 根据用户id获取mac地址
     * @param Attendance $attendance 考勤基本配置
     * @param $user_id 用户id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function getMacAddress(Attendance $attendance, $user_id)
    {
        return $attendance->usermacs()->where('user_id', $user_id)->first();
    }

    public function checkMacUsed(Attendance $attendance, $mac_address)
    {
        return $attendance->usermacs()->where('mac_address', $mac_address)->count() > 0;
    }

    /**
     * 检测某日是否放假日期
     * @param Attendance $attendance 考勤基本配置
     * @param $day 日期
     * @return bool
     */
    public function checkIsexceptionDayByDay(Attendance $attendance, $day)
    {
        return $attendance->exceptiondays()->where('day', $day)->exists();
    }

    /**
     * 获取区间段的工作日和休息日
     * @param Attendance $attendance
     * @param Carbon $startDay
     * @param Carbon $endDay
     * @return array
     */
    public function groupDayArray(Attendance $attendance,Carbon $startDay,Carbon $endDay)
    {
        $weekDayList = [];
        $restDayList = [];
        $allDayList = [];
        $weekDay = [];
        foreach ($attendance->clocksets as $clockset) {
            $weekDay[$clockset->week] = $clockset->is_weekday;
        }
        $exceptionDay = $attendance->exceptiondays()->pluck('day')->toArray();
        while ($startDay->lte($endDay)) {
            $day = $startDay->format('Y-m-d');
            if (!empty($weekDay[$startDay->englishDayOfWeek]) && !in_array($day, $exceptionDay)) {
                $weekDayList[] = $day;
            }else {
                $restDayList[] = $day;
            }
            $allDayList[] = $day;
            $startDay->addDay();
        }
        return ['week' => $weekDayList, 'rest' => $restDayList, 'all' => $allDayList];
    }

    /**
     * 获取某日的考勤记录
     * @param Attendance $attendance 考勤基本配置
     * @param $day 日期
     * @param $user_id 用户id
     * @return array
     */
    public function getOnedayClockin(Attendance $attendance, $day, $user_id)
    {
        $retList = [
            'morning' => [
                'time' => '',
                'status' => 0
            ],
            'afternoon' => [
                'time' => '',
                'status' => 0
            ],
            'evening' => [
                'time' => '',
                'status' => 0
            ]
        ];
        $list = $attendance->clockins()->where([
            ['user_id', '=', $user_id],
            ['day', '=', $day]
        ])->get();
        foreach ($list as $item) {
            $retList[$item->type]['time'] = $item->time;
            $retList[$item->type]['status'] = $item->status;
        }
        return $retList;
    }


}
