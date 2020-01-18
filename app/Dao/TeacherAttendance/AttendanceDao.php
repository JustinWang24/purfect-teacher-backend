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

    public function groupDayArray(Attendance $attendance,Carbon $startDay,Carbon $endDay)
    {
        $weekDayList = [];
        $restDayList = [];
        $allDayList = [];
        $week = $attendance->clocksets()->pluck('week')->toArray();
        $exceptionDay = $attendance->exceptiondays()->pluck('day')->toArray();

        while ($startDay->lte($endDay)) {
            $day = $startDay->format('Y-m-d');
            if (in_array($startDay->englishDayOfWeek, $week) && !in_array($day, $exceptionDay)) {
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
            'morning' => [],
            'afternoon' => [],
            'evening' => []
        ];
        $list = $attendance->clockins()->where([
            ['user_id', '=', $user_id],
            ['day', '=', $day]
        ])->get();
        foreach ($list as $item) {
            $retList[$item['type']] = $item;
        }
        return $retList;
    }


}
