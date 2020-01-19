<?php

namespace App\Dao\TeacherAttendance;

use App\Models\TeacherAttendance\Attendance;
use App\Models\TeacherAttendance\Clockin;
use App\Models\TeacherAttendance\Clockset;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;

class ClockinDao
{
    /**
     * 创建考勤记录
     * @param $data
     * @return MessageBag
     */
    public function create($data)
    {
        try{
            $result = Clockin::create($data);
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功', $result);
        }catch (\Exception $e) {
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }

    public function getList(Attendance $attendance, Carbon $monthStart,Carbon $monthEnd)
    {
        $dao = new AttendanceDao();
        $clockins = $attendance->clockins()->where([
            ['day', '>=', $monthStart->format('Y-m-d')],
            ['day', '<=', $monthEnd->format('Y-m-d')]
        ])->orderBy('day','asc')->get();

        $groupDays = $dao->groupDayArray($attendance, $monthStart, $monthEnd);
        //出勤临时列表
        $clockinList = [];
        //迟到+严重列表
        $lateList = [];
        //早退列表
        $earlyList = [];
        //补卡列表
        $applyList = [];
        //缺卡列表
        $missList = [];
        foreach ($clockins as $clockin) {
            if (!isset($clockinList[$clockin->day])) {
                $clockinList[$clockin->day] = [
                    'morning' => [],
                    'afternoon' => [],
                    'evening' => []
                ];
            }
            $clockinList[$clockin->day][$clockin->type] = $clockin;
            if ($clockin->status == Clockin::STATUS_LATE || $clockin->status == Clockin::STATUS_LATER) {
                $lateList[] = $clockin;
            }
            if ($clockin->status == Clockin::STATUS_EARLY) {
                $earlyList[] = $clockin;
            }
            if ($clockin->source == Clockin::SOURCE_APPLY) {
                $applyList[] = $clockin;
            }
        }
        foreach ($groupDays['week'] as $groupDay) {
            if (isset($clockinList[$groupDay])) {
                if (empty($clockinList[$groupDay]['morning'])) {
                    $missList[] = ['day' => $groupDay, 'type' => 'morning'];
                }
                if ($attendance->using_afternoon && empty($clockinList[$groupDay]['afternoon'])) {
                    $missList[] = ['day' => $groupDay, 'type' => 'afternoon'];
                }
                if (empty($clockinList[$groupDay]['evening'])) {
                    $missList[] = ['day' => $groupDay, 'type' => 'evening'];
                }
            }
        }

        //出勤列表
        $normalList = [];
        $clockinKey =[];
        foreach ($clockinList as $day => $clockin) {
            $normalList[] =['day' => $day, 'info' => $clockin];
            $clockinKey[] = $day;
        }

        //旷工列表
        $notList = array_diff($groupDays['week'], $clockinKey);

        return [
            'normal' => $normalList,
            'rest' => $groupDays['rest'],
            'not' => $notList,
            'late' => $lateList,
            'early' => $earlyList,
            'miss' => $missList,
            'apply' => $applyList,
            'all' => $groupDays['all']
        ];
    }

    /**
     * 检测打卡到哪一步type null已结束 状态status 0为不可打卡
     * @param $attendance 考勤基本配置
     * @param $clockset 考勤详细配置
     * @param $clockin 考勤记录
     * @return array
     */
    public function checkClockinStatus(Attendance $attendance, Clockset $clockset, $clockin)
    {
        $return = [
            'status' => Clockin::STATUS_NONE,
            'type' => 'morning'
        ];
        $day = Carbon::now()->format('Y-m-d');
        $time = Carbon::now()->format('H:i:s');
        $dateTime = Carbon::parse($day . $time)->timestamp;
        $startTime = Carbon::parse($day . $clockset->start)->timestamp;
        $endTime = Carbon::parse($day . $clockset->end)->timestamp;
        $morningTime = Carbon::parse($day . $clockset->morning)->timestamp;
        $morningLateTime = Carbon::parse($day . $clockset->morning_late)->timestamp;
        $afternoonTime = Carbon::parse($day . $clockset->afternoon)->timestamp;
        $afternoonLateTime = Carbon::parse($day . $clockset->afternoon_late)->timestamp;
        $eveningTime = Carbon::parse($day . $clockset->evening)->timestamp;

        //下个打卡节点
        $clockin['morning'] && $return['type'] = $attendance->using_afternoon ? 'afternoon' : 'evening';
        $clockin['afternoon'] && $return['type'] = 'evening';
        $clockin['evening'] && $return['type'] = null;


        if ($startTime > $dateTime) {
            return $return;
        }
        if ($endTime < $dateTime || !$return['type']) {
            $return['type'] = null;
            return $return;
        }
        if ($attendance->using_afternoon) {
            if ($return['type'] == 'morning') {
                if ($dateTime < $morningTime) {
                    $return['status'] = Clockin::STATUS_NORMAL;
                }elseif ($dateTime < $morningLateTime) {
                    $return['status'] = Clockin::STATUS_LATE;
                }elseif ($dateTime < $afternoonTime) {
                    $return['status'] = Clockin::STATUS_LATER;
                }elseif ($dateTime < $afternoonLateTime) {
                    //上午已经缺卡
                    $return['type'] = 'afternoon';
                    $return['status'] = Clockin::STATUS_LATE;
                }elseif ($dateTime < $eveningTime) {
                    //上午已经缺卡
                    $return['type'] = 'afternoon';
                    $return['status'] = Clockin::STATUS_LATER;
                }else {
                    //中午已经缺卡
                    $return['type'] = 'evening';
                    $return['status'] = Clockin::STATUS_NORMAL;
                }
            }elseif ($return['type'] == 'afternoon') {
                if ($dateTime < $morningTime) {
                    $return['status'] = Clockin::STATUS_NONE;
                }elseif ($dateTime < $afternoonTime) {
                    $return['status'] = Clockin::STATUS_NORMAL;
                }elseif ($dateTime < $afternoonLateTime) {
                    $return['status'] = Clockin::STATUS_LATE;
                }elseif ($dateTime < $eveningTime) {
                    $return['status'] = Clockin::STATUS_LATER;
                }else {
                    //中午已经缺卡
                    $return['type'] = 'evening';
                    $return['status'] = Clockin::STATUS_NORMAL;
                }
            }else {
                if ($dateTime < $afternoonTime) {
                    $return['status'] = Clockin::STATUS_NONE;
                }elseif ($dateTime < $eveningTime) {
                    $return['status'] = Clockin::STATUS_EARLY;
                }else {
                    $return['status'] = Clockin::STATUS_NORMAL;
                }
            }
        }else {
            if ($return['type'] == 'morning') {
                if ($dateTime < $morningTime) {
                    $return['status'] = Clockin::STATUS_NORMAL;
                }elseif ($dateTime < $morningLateTime) {
                    $return['status'] = Clockin::STATUS_LATE;
                }elseif ($dateTime < $eveningTime) {
                    $return['status'] = Clockin::STATUS_LATER;
                }else {
                    //上午已经缺卡
                    $return['type'] = 'evening';
                    $return['status'] = Clockin::STATUS_NORMAL;
                }
            }else {
                if ($dateTime < $morningTime) {
                    $return['status'] = Clockin::STATUS_NONE;
                }elseif ($dateTime < $eveningTime) {
                    $return['status'] = Clockin::STATUS_EARLY;
                }else {
                    $return['status'] = Clockin::STATUS_NORMAL;
                }
            }
        }
        return $return;
    }
}
