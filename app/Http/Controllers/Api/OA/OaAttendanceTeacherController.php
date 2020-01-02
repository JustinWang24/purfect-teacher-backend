<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\OA\OaAttendanceTeacherDao;
use App\Models\OA\OaAttendanceTeacher;
use App\Models\OA\OaAttendanceTeacherGroup;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OaAttendanceTeacherController extends Controller
{
    /**
     * 打卡
     * @param Request $request
     * @return string
     */
    public function postTodayInfo(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $wifi = strip_tags($request->get('wifi'));
        $mac_address = strip_tags($request->get('mac_address'));
        $dao = new OaAttendanceTeacherDao();
        $row = $dao->getTodayRecord($user->id,$schoolId);
        $time = time();
        $sqlTime = date('Y-m-d H:i:s',$time);
        $data = [];
        $group = $dao->getGroupInfo($user->id,$schoolId);
        $afternoonPoint = strtotime($group->morning_offline_time) + (strtotime($group->afternoon_online_time)-strtotime($group->morning_offline_time))/2;
        $nightPoint = strtotime($group->afternoon_offline_time) + (strtotime($group->night_online_time)-strtotime($group->afternoon_offline_time))/2;
        if ($row) {
            /**
             *  在等号区间内的打卡行为以下班时间和上班时间中间时间点来计算，超过中间时间点的算上班，中间时间点以前的算下班。

            ​      9点         12点                   15点              18点                19点              21点
            =======|            |====afternoonPoint====|                 |====nightPoint====|                 |========

             */
            if ($time <= $afternoonPoint){
                if (empty($row->morning_online_time)) {
                    $data['morning_online_time'] = $sqlTime;
                } else {
                    $data['morning_offline_time'] = $sqlTime;
                }

            } elseif($time <= $nightPoint) {
                if (empty($row->afternoon_online_time)) {
                    $data['afternoon_online_time'] = $sqlTime;
                } else {
                    $data['afternoon_offline_time'] = $sqlTime;
                }
            } else {
                if (empty($row->night_online_time)) {
                    $data['night_online_time'] = $sqlTime;
                } else {
                    $data['night_offline_time'] = $sqlTime;
                }
            }
            $data['wifi']           = $wifi;
            $data['mac_address']    = $mac_address;
            $result = $dao->updateAttendanceTeacher($user->id,$schoolId,$data);
        } else {
            $data = [
                'user_id'   => $user->id,
                'wifi'      => $wifi,
                'mac_address'=> $mac_address,
                'check_in_date'=>date('Y-m-d'),
                'school_id' => $schoolId,
            ];
            if ($time <= $afternoonPoint){
                $data['morning_online_time'] = $sqlTime;
            } elseif($time <= $nightPoint) {
                $data['afternoon_online_time'] = $sqlTime;
            } else {
                $data['night_online_time'] = $sqlTime;
            }
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
        $dao = new OaAttendanceTeacherDao();
        $group = $dao->getGroupInfo($user->id,$schoolId);
        if (empty($group)) {
            return JsonBuilder::Error('您还没有加入一个考勤组，请联系管理员');
        }
        $record = $dao->getRecord($user->id,$schoolId,$day);
        if(empty($record)) {
            return JsonBuilder::Success('没有记录');
        }
        $status = $dao->getStatus($record);
        $output = [
            'wifi_name' => $record->wifi,
            'user_name' => $record->user->name,
            'head_img'  => $record->user->profile->avatar,
            'group_name'=> $group->name,
            'date'      => $day,
            'morning_online_time'       => $group->morning_online_time,
            'morning_online_mine'       => $record->morning_online_mine,
            'morning_online_status'     => $status['morning_online_status'],
            'morning_offline_time'      => $group->morning_offline_time,
            'morning_offline_mine'      => $record->morning_offline_mine,
            'morning_offline_status'    => $status['morning_offline_status'],
            'afternoon_online_time'     => $group->afternoon_online_time,
            'afternoon_online_mine'     => $record->afternoon_online_mine,
            'afternoon_online_status'   => $status['afternoon_online_status'],
            'afternoon_offline_time'    => $group->afternoon_offline_time,
            'afternoon_offline_mine'    => $record->afternoon_offline_mine,
            'afternoon_offline_status'  => $status['afternoon_offline_status'],
            'night_online_time'         => $group->night_online_time,
            'night_online_mine'         => $record->night_online_mine,
            'night_online_status'       => $status['night_online_status'],
            'night_offline_time'        => $group->night_offline_time,
            'night_offline_mine'        => $record->night_offline_mine,
            'night_offline_status'      => $status['night_offline_status'],
            'button_status'             => $status['button_status'],
            'mac_address'               => $record->mac_address,
        ];
        return JsonBuilder::Success($output);

    }


    public function mac_add(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new OaAttendanceTeacherDao();
        $oldMacAddress = $dao->getMacAddress($user->id, $schoolId);
        if ($oldMacAddress) {
            return JsonBuilder::Success('操作成功');
        }
        $mac_address = strip_tags($request->get('mac_address'));
        if (empty($mac_address)) {
            return JsonBuilder::Error('数据错误，没有获取到设备标识');
        }
        $result = $dao->updateMacAddress($user->id,$mac_address);
        if($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }


    }



}
