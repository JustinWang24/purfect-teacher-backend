<?php

namespace App\Dao\TeacherAttendance;

use App\Models\TeacherAttendance\Attendance;
use App\Models\TeacherAttendance\Clockin;
use App\Models\TeacherAttendance\Clockset;
use App\Models\TeacherAttendance\Leave;
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
            foreach ($data as $key => $val) {
                if (in_array($key, ['time', 'status', 'source'])) {
                    $data1[$key] = $val;
                }else {
                    $data2[$key] = $val;
                }
            }
            $result = Clockin::updateOrInsert($data1, $data2);
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功', $result);
        }catch (\Exception $e) {
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }

    public function getOneMonthCount(Attendance $attendance, Carbon $monthStart, Carbon $monthEnd) {
        $return = [
            'morning' => [
                'ok' => ['count' => 0, 'users' => 0, 'list' => []],
                'late' => ['count' => 0, 'users' => 0, 'list' => []],
                'later' => ['count' => 0, 'users' => 0, 'list' => []],
                'not' => ['count' => 0, 'users' => 0, 'list' => []],
            ],
            'afternoon' => [
                'ok' => ['count' => 0, 'users' => 0, 'list' => []],
                'late' => ['count' => 0, 'users' => 0, 'list' => []],
                'later' => ['count' => 0, 'users' => 0, 'list' => []],
                'not' => ['count' => 0, 'users' => 0, 'list' => []],
            ],
            'evening' => [
                'ok' => ['count' => 0, 'users' => 0, 'list' => []],
                'early' => ['count' => 0, 'users' => 0, 'list' => []],
                'not' => ['count' => 0, 'users' => 0, 'list' => []],
            ]
        ];

        $userOrganizationList = UserOrganization::whereIn('organization_id', $attendance->organizations()->pluck('organization_id')->toArray())->get();
        $userList = [];
        foreach ($userOrganizationList as $item) {
            $userList[$item->user->id] = [
                'userid' => $item->user->id,
                'name' => $item->user->name,
                'avatar' => $item->user->profile->avatar,
                'status' => Clockin::STATUS_NONE,
                'day' => '',
                'time' => ''
            ];
        }


    }

    public function getOneDayCount(Attendance $attendance, $day) {
        $return = [
            'morning' => [
                'ok' => ['count' => 0, 'list' => []],
                'late' => ['count' => 0, 'list' => []],
                'later' => ['count' => 0, 'list' => []],
                'not' => ['count' => 0, 'list' => []],
            ],
            'afternoon' => [
                'ok' => ['count' => 0, 'list' => []],
                'late' => ['count' => 0, 'list' => []],
                'later' => ['count' => 0, 'list' => []],
                'not' => ['count' => 0, 'list' => []],
            ],
            'evening' => [
                'ok' => ['count' => 0, 'list' => []],
                'early' => ['count' => 0, 'list' => []],
                'not' => ['count' => 0, 'list' => []],
            ]
        ];
        $userOrganizationList = UserOrganization::whereIn('organization_id', $attendance->organizations()->pluck('organization_id')->toArray())->get();
        $userList = [];
        foreach ($userOrganizationList as $item) {
            $userList[$item->user->id] = [
                'userid' => $item->user->id,
                'name' => $item->user->name,
                'avatar' => $item->user->profile->avatar,
                'status' => Clockin::STATUS_NONE,
                'time' => ''
            ];
        }

        $clockins = $attendance->clockins()->where('day', '=', $day)->orderBy('id', 'desc')->get();
        $hasUserId = ['morning' => [], 'afternoon' => [], 'evening' => []];
        foreach ($clockins as $clockin) {
            //该用户已不在此组内
            if (!isset($userList[$clockin->user_id])) {
                continue;
            }
            $hasUserId[$clockin->type][] = $clockin->user_id;
            $user = $userList[$clockin->user_id];
            $user['status'] = $clockin->status;
            $user['time'] = $clockin->time;
            switch ($clockin->status) {
                case Clockin::STATUS_NORMAL:
                    $return[$clockin->type]['ok']['count']++;
                    $return[$clockin->type]['ok']['list'][] = $user;
                    break;
                case Clockin::STATUS_LATE:
                    $return[$clockin->type]['late']['count']++;
                    $return[$clockin->type]['late']['list'][] = $user;
                    break;
                case Clockin::STATUS_LATER:
                    $return[$clockin->type]['later']['count']++;
                    $return[$clockin->type]['later']['list'][] = $user;
                    break;
                case Clockin::STATUS_EARLY:
                    $return[$clockin->type]['early']['count']++;
                    $return[$clockin->type]['early']['list'][] = $user;
                    break;
                default:
                    break;
            }
        }
        foreach ($userList as $userId => $user) {
            if (!in_array($userId, $hasUserId['morning'])) {
                $return['morning']['not']['count']++;
                $return['morning']['not']['list'][] = $user;
            }
            if (!in_array($userId, $hasUserId['afternoon'])) {
                $return['afternoon']['not']['count']++;
                $return['afternoon']['not']['list'][] = $user;
            }
            if (!in_array($userId, $hasUserId['evening'])) {
                $return['evening']['not']['count']++;
                $return['evening']['not']['list'][] = $user;
            }
        }
        return $return;
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
            'away' => 0,//外出人数
            'travel' => 0,//出差人数
            'normal_list' => [],
            'not_list' => []
        ];
        foreach ($attendanceIdArr as $attendanceId) {
            $attendance = Attendance::find($attendanceId);
            //该考勤组下总成员
            $return['all'] += UserOrganization::whereIn('organization_id', $attendance->organizations()->pluck('organization_id')->toArray())
                ->count();

            //该考勤组下已打卡人员
            $return['normal'] += $attendance->clockins()->where('day', '=', $day)->distinct()->count('user_id');
            $return['late'] += $attendance->clockins()->where('day', '=', $day)->whereIn('status', [Clockin::STATUS_LATE, Clockin::STATUS_LATER])->distinct()->count('user_id');
            //请假、外出、出差人员
            $return['leave'] += $attendance->leaveDetails()->where('day', '=', $day)->where('source', Leave::SOURCE_LEAVE)->distinct()->count('user_id');
            $return['away'] += $attendance->leaveDetails()->where('day', '=', $day)->where('source', Leave::SOURCE_AWAY)->distinct()->count('user_id');
            $return['travel'] += $attendance->leaveDetails()->where('day', '=', $day)->where('source', Leave::SOURCE_TRAVEL)->distinct()->count('user_id');

            //总人员列表
            $userList = UserOrganization::whereIn('organization_id', $attendance->organizations()->pluck('organization_id')->toArray())
                ->get();
            foreach ($userList as $userOrganization) {
                //今日打卡状态
                $clockin = $attendance->clockins()->where(['user_id' => $userOrganization->user->id, 'day' => $day])->pluck('status', 'type')->toArray();
                $tags = $attendance->leaveDetails()->where(['user_id' => $userOrganization->user->id, 'day' => $day])->distinct()->pluck('source')->ToArray();
                $userInfo = [
                    'userid' => $userOrganization->user->id,
                    'name' => $userOrganization->user->name,
                    'avatar' => $userOrganization->user->profile->avatar,
                    'title' => $attendance->title,
                    'tags' => $tags ?? [],
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

    public function getList(Attendance $attendance, Carbon $monthStart,Carbon $monthEnd, $userId)
    {
        $dao = new AttendanceDao();
        $clockins = $attendance->clockins()->where('user_id', $userId)
            ->where([
                ['day', '>=', $monthStart->format('Y-m-d')],
                ['day', '<=', $monthEnd->format('Y-m-d')]
        ])->orderBy('day','asc')->get();

        $clocksets = [];
        foreach ($attendance->clocksets as $clockset) {
            $clocksets[$clockset->week] = $clockset;
        }

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
        //请假列表
        $leaveList = ['num' => 0, 'list' => []];
        //外出列表
        $awayList = ['num' => 0, 'list' => []];
        //出差列表
        $travelList = ['num' => 0, 'list' => []];

        $leaveALl = $attendance->leaves()->where([
            ['user_id', '=', $userId],
            ['start', '<=', $monthStart->format('Y-m-d H:i:s')],
            ['end', '>=', $monthEnd->format('Y-m-d H:i:s')]
        ])->get();

        if ($leaveALl) {
            foreach ($leaveALl as $leave) {
                $leaveArr = [
                    'start' => substr($leave->start, 0, 16),
                    'end' => substr($leave->end, 0, 16),
                    'daynumber' => $leave->daynumber
                ];
                if ($leave->source == Leave::SOURCE_LEAVE) {
                    $leaveList['num'] += $leave->daynumber;
                    $leaveList['list'][] = $leaveArr;
                }
                if ($leave->source == Leave::SOURCE_AWAY) {
                    $awayList['num'] += $leave->daynumber;
                    $awayList['list'][] = $leaveArr;
                }
                if ($leave->source == Leave::SOURCE_TRAVEL) {
                    $travelList['num'] += $leave->daynumber;
                    $travelList['list'][] = $leaveArr;
                }
            }
        }
        $leaveList['num'] = strval($leaveList['num']);
        $awayList['num'] = strval($awayList['num']);
        $travelList['num'] = strval($travelList['num']);

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
                $week = Carbon::parse($groupDay)->englishDayOfWeek;
                if ($clockinList[$groupDay]['morning']['status'] == Clockin::STATUS_NONE) {
                    $missList[] = ['day' => $groupDay, 'type' => 'morning', 'set_time' => substr($clocksets[$week]->morning, 0, 5)];
                }
                if ($attendance->using_afternoon && $clockinList[$groupDay]['afternoon']['status'] == Clockin::STATUS_NONE) {
                    $missList[] = ['day' => $groupDay, 'type' => 'afternoon', 'set_time' => substr($clocksets[$week]->afternoon, 0, 5)];
                }
                if ($clockinList[$groupDay]['evening'] == Clockin::STATUS_NONE) {
                    $missList[] = ['day' => $groupDay, 'type' => 'evening', 'set_time' => substr($clocksets[$week]->evening, 0, 5)];
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
            'not' => $notList ? array_values($notList) : [],
            'late' => $lateList,
            'early' => $earlyList,
            'miss' => $missList,
            'apply' => $applyList,
            'all' => $groupDays['all'],
            'leave' => $leaveList,
            'travel' => $travelList,
            'away' => $awayList
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
        $afternoonStartTime = Carbon::parse($day . $clockset->afternoon_start)->timestamp;
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
                }elseif ($dateTime < $afternoonStartTime) {
                    $return['status'] - Clockin::STATUS_LATER;
                }elseif ($dateTime < $afternoonTime) {
                    //上午已经缺卡
                    $return['type'] = 'afternoon';
                    $return['status'] = Clockin::STATUS_NORMAL;
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
                if ($dateTime < $afternoonStartTime) {
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
