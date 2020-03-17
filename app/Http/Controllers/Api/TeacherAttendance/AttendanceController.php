<?php

namespace App\Http\Controllers\Api\TeacherAttendance;

use App\Dao\TeacherAttendance\AttendanceDao;
use App\Dao\TeacherAttendance\ClockinDao;
use App\Dao\TeacherAttendance\UserMacDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherAttendance\AttendanceRequest;
use App\Models\TeacherAttendance\Clockin;
use App\Utils\JsonBuilder;
use App\Utils\Misc\Contracts\Title;
use Carbon\Carbon;
use Psy\Util\Json;

class AttendanceController extends Controller
{
    //
    public function getTodayInfo(AttendanceRequest $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $day = Carbon::parse($request->getInputDay())->format('Y-m-d');
        $enDay = Carbon::parse($request->getInputDay())->englishDayOfWeek;
        //获取用户所在组织
        $organizationIdArr = $user->organizations->pluck('organization_id')->toArray();
        $dao = new AttendanceDao();
        //获取考勤配置
        $attendance = $dao->getByOrganizationIdArr($organizationIdArr, $schoolId);
        if (empty($attendance)) {
            return JsonBuilder::Error('您还没有加入考勤组，请联系管理员');
        }
        //获取当日配置
        $clockset = $dao->getOnedayClockset($attendance, $enDay);
        $clockset->start = substr($clockset->start,0, 5);
        $clockset->end = substr($clockset->end,0, 5);
        $clockset->morning = substr($clockset->morning,0, 5);
        $clockset->morning_late = substr($clockset->morning_late,0, 5);
        $clockset->afternoon_start = substr($clockset->afternoon_start,0, 5);
        $clockset->afternoon = substr($clockset->afternoon,0, 5);
        $clockset->afternoon_late = substr($clockset->afternoon_late,0, 5);
        $clockset->evening = substr($clockset->evening,0, 5);

        //获取当日记录
        $clockin = $dao->getOnedayClockin($attendance, $day, $user->id);

        $clockin['morning']['time'] = substr($clockin['morning']['time'], 0, 5);
        $clockin['afternoon']['time'] = substr($clockin['afternoon']['time'], 0, 5);
        $clockin['evening']['time'] = substr($clockin['evening']['time'], 0, 5);

        //获取Mac
        $mac = $dao->getMacAddress($attendance, $user->id);
        $showButton = false;
        $nextType = '';
        //只有今日才可能 存在打卡按钮
        if ($day == Carbon::now()->format('Y-m-d')) {
            $clockinDao = new ClockinDao();
            $check = $clockinDao->checkClockinStatus($attendance, $clockset, $clockin);
            $nextType = $check['type'] ?? '';
            $showButton = $check['status'] == Clockin::STATUS_NONE ? false : true;
        }



        $return = [
            'attendance' => $attendance,
            'clockset' => $clockset,
            'clockin' => $clockin,
            'mac_address' => $mac['mac_address'],
            'date' => $day,
            'show' => $showButton,
            'next' => $nextType
        ];

        return JsonBuilder::Success($return);
    }

    public function postTodayInfo(AttendanceRequest $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $day = Carbon::now()->format('Y-m-d');//日期
        $enDay = Carbon::now()->englishDayOfWeek;//英文的周
        $time = Carbon::now()->format('H:i:s');//时间
        $wifi = $request->getInputWifi();//wifi
        $useMac = $request->getInputMacAddress();//mac

        //获取用户所在组织
        $organizationIdArr = $user->organizations->pluck('organization_id')->toArray();
        $dao = new AttendanceDao();
        //获取考勤配置
        $attendance = $dao->getByOrganizationIdArr($organizationIdArr, $schoolId);
        if (empty($attendance)) {
            return JsonBuilder::Error('您还没有加入考勤组，请联系管理员');
        }
        if ($attendance->wifi_name != $wifi) {
            return JsonBuilder::Error('请连接指定WiFi打卡');
        }
        //获取今日配置
        $clockset = $dao->getOnedayClockset($attendance, $enDay);
        //获取Mac
        $mac = $dao->getMacAddress($attendance, $user->id);
        if (empty($mac)) {
            return JsonBuilder::Error('请先绑定Mac地址');
        }
        if ($mac->mac_address != $useMac) {
            return JsonBuilder::Error('请使用绑定的手机打卡');
        }

        //获取今日记录
        $clockin = $dao->getOnedayClockin($attendance, $day, $user->id);

        //检测是否能打卡
        $clockinDao = new ClockinDao();
        $check = $clockinDao->checkClockinStatus($attendance, $clockset, $clockin);
        if ($check['status'] == Clockin::STATUS_NONE || !$check['type']) {
            return JsonBuilder::Error('当前不可打卡');
        }

        $clockinDao = new ClockinDao();
        $data = [
            'teacher_attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'day' => $day,
            'time' => $time,
            'type' => $check['type'],
            'status' => $check['status'],
            'source' => Clockin::SOURCE_APP
        ];
        $result = $clockinDao->create($data);
        if($result->isSuccess()) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    public function macAdd(AttendanceRequest $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new AttendanceDao();
        //获取用户所在组织
        $organizationIdArr = $user->organizations->pluck('organization_id')->toArray();
        //获取考勤配置
        $attendance = $dao->getByOrganizationIdArr($organizationIdArr, $schoolId);
        if (empty($attendance)) {
            return JsonBuilder::Error('您还没有加入考勤组，请联系管理员');
        }

        $mac = $dao->getMacAddress($attendance, $user->id);
        if (!empty($mac)) {
            return JsonBuilder::Error('您已绑定Mac地址');
        }

        $inputMac = $request->getInputMacAddress();
        if ($dao->checkMacUsed($attendance, $inputMac)) {
            return JsonBuilder::Error('该手机已绑定其他号码');
        }

        $macDao = new UserMacDao();
        $data = [
            'teacher_attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'mac_address' => $inputMac
        ];

        $result = $macDao->create($data);
        if($result->isSuccess()) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    public function getGroupList(AttendanceRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        //获取用户拥有的管理权限的考勤组
        $dao = new AttendanceDao();
        $list = $dao->getListByManagerId($user->id, $schoolId);
        $return = [
            ['groupid' => 0, 'title' => '全部']
        ];
        foreach ($list as $item) {
            $return[] = ['groupid' => $item->id, 'title' => $item->title];
        }
        return JsonBuilder::Success($return);
    }

    public function getManageDayCount(AttendanceRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $day = $request->getInputDay();
        $groupid = $request->getInputGroupId();
        //获取用户拥有的管理权限的考勤组
        $dao = new AttendanceDao();
        $list = $dao->getListByManagerId($user->id, $schoolId);
        $groupIdList = [];//用户要查询的组ID 验证权限
        foreach ($list as $item) {
            //如果只是查询某一分组数据
            if ($groupid) {
                if ($item->id == $groupid) {
                    $groupIdList[] = $groupid;
                }
            }else {
                $groupIdList[] = $item->id;
            }
        }
        if (empty($groupIdList)) {
            return JsonBuilder::Error('权限不足');
        }
        $clockinDao = new ClockinDao();
        $result = $clockinDao->getOneDayList($groupIdList, $day);
        $result['groupid'] = (int) $groupid;
        $result['day'] = $day;
        return JsonBuilder::Success($result);
    }
    public function getMonthCount(AttendanceRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new AttendanceDao();
        $monthStart = Carbon::parse($request->getInputMonth())->firstOfMonth();
        $month = $monthStart->format('Y-m');
        if (Carbon::parse($request->getInputMonth())->isSameMonth(Carbon::today())) {
            //当月只显示到今日
            $monthEnd = Carbon::today();
        }else {
            $monthEnd = Carbon::parse($request->getInputMonth())->lastOfMonth();
        }
        //获取用户所在组织
        $organizationIdArr = $user->organizations->pluck('organization_id')->toArray();
        //获取考勤配置
        $attendance = $dao->getByOrganizationIdArr($organizationIdArr, $schoolId);
        if (empty($attendance)) {
            return JsonBuilder::Error('您还没有加入考勤组，请联系管理员');
        }
        $clockinDao = new ClockinDao();
        $countList = $clockinDao->getList($attendance, $monthStart, $monthEnd, $user->id);

        $return = [
            'attendance' => [
                'title' => $attendance->title,
                'wifi_name' => $attendance->wifi_name,
                'using_afternoon' => $attendance->using_afternoon
            ],
            'month' => $month,
        ];
        return JsonBuilder::Success(array_merge($return, $countList));
    }

}
