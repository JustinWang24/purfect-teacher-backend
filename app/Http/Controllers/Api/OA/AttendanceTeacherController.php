<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\OA\AttendanceTeacherDao;
use App\Models\OA\AttendanceTeachersMessage;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceTeacherController extends Controller
{
    public function postTodayInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $wifi = strip_tags($request->get('wifi'));
        $mac_address = strip_tags($request->get('mac_address'));
        $dao = new AttendanceTeacherDao();
        $row = $dao->getTodayRecord($user->id,$schoolId);
        if ($row) {
            $data = [
                'wifi'      => $wifi,
                'mac_address'=> $mac_address,
                'offline_mine'=>date('Y-m-d H:i:s'),
            ];
            $result = $dao->updateAttendanceTeacher($user->id,$schoolId,$data);
        } else {
            $data = [
                'user_id'   => $user->id,
                'wifi'      => $wifi,
                'mac_address'=> $mac_address,
                'check_in_date'=>date('Y-m-d'),
                'school_id' => $schoolId,
                'online_mine'=>date('Y-m-d H:i:s'),
            ];
            $result = $dao->createAttendanceTeacher($data);
        }
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }
    public function getTodayInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $day = date('Y-m-d', strtotime($request->get('day',date('Y-m-d'))));
        $dao = new AttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        if (empty($group)) {
            return JsonBuilder::Error('您还没有加入一个考勤组，请联系管理员');
        }
        $record = $dao->getRecord($user->id,$schoolId,$day);
        if (empty($record)) {
            return JsonBuilder::Error('您还没有打卡记录，请先打卡');
        }
        $status = $dao->getStatus($record);
        $output = [
            'wifi_name' => $record->wifi,
            'user_name' => $record->user->name,
            'head_img'  => $record->user->profile->avatar,
            'group_name'=> $group->name,
            'date'      => $day,
            'online_time'=> $group->online_time,
            'online_mine'=> $record->online_mine,
            'online_status'=> $status['online_status'],
            'offline_time' => $group->offline_time,
            'offline_mine' => $record->offline_mine,
            'offline_status' => $status['offline_status'],
            'button_status'  =>  $status['button_status'],
            'mac_address'   => $record->mac_address,

        ];
        return JsonBuilder::Success($output);

    }
    public function getMissList(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $month = strip_tags($request->get('month'));
        $dao = new AttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $result = $dao->getMonthList($user->id,$month);
        $timeArr = $dao->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $missDay = 0;
        $miss_list = [];
        while ($start<$end)
        {
            if (!isset($result[$start]) && date('N',strptime($start))<=5) {
                $missDay++;
                $status = $dao->getMessageOptStatus($user->id,$start);

                //上午下午都补卡按照原来的结构只能给2条记录
                $miss_list[] = [
                    'day'=>$start,
                    'time'=>$group->online_time,
                    'status'=>$status,
                    'type'=> 'offline',
                ];
                $miss_list[] = [
                    'day'=>$start,
                    'time'=>$group->online_time,
                    'status'=>$status,
                    'type'=> 'online',
                ];
            } else {
                $has = false;
                if (empty($result[$start]->online_mine)) {
                    $missDay++;
                    $has = true;//统计天数已经加一
                    $status = $dao->getMessageRecord($user->id,$start);
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->offline_mine)) {
                    $status = $dao->getMessageRecord($user->id,$start);
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->online_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                    if (!$has) {
                        $missDay++;
                    }
                }

            }
            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }
        $data = [
            'user_name' => $user->name,
            'head_img'  => $user->profile->avatar,
            'group_name'=> $group->name,
            'month_select'=>$month,
            'miss_count' => $missDay,
            'miss_list' => $miss_list
        ];
        return JsonBuilder::Success($data);
    }
    public function getJsMonthCount(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $month = strip_tags($request->get('month'));
        $dao = new AttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $result = $dao->getMonthList($user->id,$month);
        $timeArr = $dao->getStartAndEndArr($month);
        $start = $timeArr['start'];
        $end   = $timeArr['end'];
        $missDay = 0;
        $days_count = 0;
        $normal_count = 0;
        $rest_count = 0;
        $not_count = 0;
        $late_count = 0;
        $early_count = 0;
        $miss_count = 0;
        $approver_count = 0;
        $normal_list = [];
        $rest_list = [];
        $not_list = [];
        $late_list = [];
        $early_list = [];
        $approver_list = [];
        $miss_list = [];
        while ($start<$end)
        {
            //统计当月天数
            $days_count++;
            //统计休息日天数
            if (date('N',strtotime($start))>5) {
                $rest_count++;
                $rest_list[] = [
                    'day'=>$start,
                ];
            }

            if (!isset($result[$start]) && date('N',strtotime($start))<=5) {
                //旷工日统计
                $not_count++;
                $not_list[] = [
                    'day'=>$start,
                ];

                //缺卡日统计
                $missDay++;
                $status = $dao->getMessageOptStatus($user->id,$start);

                //上午下午都补卡按照原来的结构只能给2条记录
                $miss_list[] = [
                    'day'=>$start,
                    'time'=>$group->online_time,
                    'status'=>$status,
                    'type'=> 'offline',
                ];
                $miss_list[] = [
                    'day'=>$start,
                    'time'=>$group->online_time,
                    'status'=>$status,
                    'type'=> 'online',
                ];
            } elseif(isset($result[$start])) {
                $status = $dao->getStatus($result[$start]);
                //出勤日统计
                $normal_count++;
                $normal_list[] = [
                    'day'=>$start,
                    'start_status'=>$status['online_status'],
                    'end_status'=>$status['offline_status'],
                ];
                //迟到
                if(strtotime($result[$start]->online_mine) > strtotime($result[$start]->check_in_date.' '.$group->online_time))
                {
                    $late_count++;
                    $late_list[] = [
                        'day'=>$start,
                        'time'=>$group->online_time,
                        'status'=>$status,
                    ];
                }
                //补卡
                if($result[$start]->status == 2) {
                    $approver_count++;
                    $approver_list[] = [
                        'day'=>$start,
                    ];
                }

                //早退
                if (strtotime($result[$start]->offline_mine) < strtotime($result[$start]->check_in_date.' '.$group->offline_time))
                {
                    $early_count++;
                    $early_list[] = [
                        'day'=>$start,
                        'time'=>$group->offline_mine,
                        'end_status'=>$status['offline_status'],
                    ];
                }


                $has = false;
                if (empty($result[$start]->online_mine)) {
                    $miss_count++;
                    $has = true;//统计天数已经加一
                    $status = $dao->getMessageRecord($user->id,$start);
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->online_time,
                        'status'=>$status,
                        'type'=> 'online',
                    ];
                } elseif(empty($result[$start]->offline_mine)) {
                    $status = $dao->getMessageRecord($user->id,$start);
                    $miss_list[] = [
                        'day'=>$start,
                        'time'=>$group->online_time,
                        'status'=>$status,
                        'type'=> 'offline',
                    ];
                    if (!$has) {
                        $miss_count++;
                    }
                }

            }
            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }
        $data = [
            'user_name' => $user->name,
            'head_img'  => $user->profile->avatar,
            'group_name'=> $group->name,
            'month_select'=>$month,
            'days_count' => $days_count,
            'normal_count'=>$normal_count,
            'normal_list' => $normal_list,
            'rest_count' => $rest_count,
            'rest_list'  => $rest_list,
            'not_count'  => $not_count,
            'early_count'=> $early_count,
            'early_list' => $early_list,
            'miss_count' => $missDay,
            'miss_list' => $miss_list,
            'approver_count' => $approver_count,
            'approver_list'  => $approver_list
        ];
        return JsonBuilder::Success($data);
    }
}
