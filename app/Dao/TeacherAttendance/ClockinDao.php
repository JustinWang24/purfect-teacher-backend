<?php

namespace App\Dao\TeacherAttendance;

use App\Models\TeacherAttendance\Attendance;
use App\Models\TeacherAttendance\Clockin;
use App\Models\TeacherAttendance\Clockset;
use App\Models\Users\UserOrganization;
use App\Utils\JsonBuilder;
use App\Utils\Misc\Contracts\Title;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    /**
     * 管理员获取某日的考勤统计
     * @param $attendanceIdArr
     * @param $day
     * @return array
     */
    public function getOneDayList($attendanceIdArr, $day)
    {
        $return = [
            'all' => 0,//全部人数
            'normal' => 0,//已打卡人数
            'not' =>0,//未打卡人数 全部-已打卡
            'late' => 0,//迟到人数
            'leave' => 0,//请假人数
            'normal_list' => [],
            'not_list' => []
        ];
        foreach ($attendanceIdArr as $attendanceId) {
            $attendance = Attendance::find($attendanceId);
            //该考勤组下总成员
            $return['all'] += UserOrganization::whereIn('organization_id', $attendance->organizations()->pluck('organization_id')->toArray())
                ->where('title_id', '=', Title::MEMBER)->count();
            //该考勤组下已打卡人员
            $return['normal'] += $attendance->clockins()->where('day', '=', $day)->distinct()->count('user_id');
            $return['late'] += $attendance->clockins()->where('day', '=', $day)->whereIn('status', [Clockin::STATUS_LATE, Clockin::STATUS_LATER])->distinct()->count('user_id');

            //总人员列表
            $userList = UserOrganization::whereIn('organization_id', $attendance->organizations()->pluck('organization_id')->toArray())
                ->where('title_id', '=', Title::MEMBER)->get();
            foreach ($userList as $userOrganization) {
                //今日打卡状态
                $clockin = $attendance->clockins()->where(['user_id' => $userOrganization->user->id, 'day' => $day])->pluck('status', 'type')->toArray();
                $userInfo = [
                    'userid' => $userOrganization->user->id,
                    'name' => $userOrganization->user->name,
                    'avatar' => $userOrganization->user->profile->avatar,
                    'title' => $attendance->title,
                    'clockin' => [
                        'using_afternoon' => $attendance->using_afternoon,
                        'morning' => $clockin['morning'] ?? Clockin::STATUS_NONE,
                        'afternoon' => $clockin['afternoon'] ?? Clockin::STATUS_NONE,
                        'evening' => $clockin['evening'] ?? Clockin::STATUS_NONE
                    ]
                ];
                if (empty($clockin)) {
                    $return['not_list'][] = $userInfo;
                }else {
                    $return['normal_list'][] = $userInfo;
                }
            }
        }

        $return['not'] = $return['all'] - $return['normal'];
        return $return;
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
                    'morning' => ['time' => '', 'status' => Clockin::STATUS_NONE],
                    'afternoon' => ['time' => '', 'status' => Clockin::STATUS_NONE],
                    'evening' => ['time' => '', 'status' => Clockin::STATUS_NONE]
                ];
            }
            $clockinList[$clockin->day][$clockin->type]['time'] = $clockin->time;
            $clockinList[$clockin->day][$clockin->type]['status'] = $clockin->status;
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
                if ($clockinList[$groupDay]['morning']['status'] == Clockin::STATUS_NONE) {
                    $missList[] = ['day' => $groupDay, 'type' => 'morning'];
                }
                if ($attendance->using_afternoon && $clockinList[$groupDay]['afternoon']['status'] == Clockin::STATUS_NONE) {
                    $missList[] = ['day' => $groupDay, 'type' => 'afternoon'];
                }
                if ($clockinList[$groupDay]['evening'] == Clockin::STATUS_NONE) {
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
        $clockin['morning']['status'] && $return['type'] = $attendance->using_afternoon ? 'afternoon' : 'evening';
        $clockin['afternoon']['status'] && $return['type'] = 'evening';
        $clockin['evening']['status'] && $return['type'] = null;

        //未到上班时间
        if ($startTime > $dateTime) {
            return $return;
        }
        //已超过结束时间或已打完卡
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
