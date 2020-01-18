<?php

namespace App\Http\Controllers\Api\TeacherAttendance;

use App\Dao\TeacherAttendance\AttendanceDao;
use App\Dao\TeacherAttendance\ClockinDao;
use App\Dao\TeacherAttendance\UserMacDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherAttendance\AttendanceRequest;
use App\Models\TeacherAttendance\Clockin;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

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
        if ($clockset) {
            //查看是否例外日期
            $dao->checkIsexceptionDayByDay($attendance, $day) && $clockset = null;
        }
        //获取当日记录
        $clockin = $dao->getOnedayClockin($attendance, $day, $user->id);

        //获取Mac
        $mac = $dao->getMacAddress($attendance, $user->id);
        $showButton = false;
        $nextType = '';
        //只有今日才可能 存在打卡按钮
        if ($day == Carbon::now()->format('Y-m-d') && $clockset) {
            $clockinDao = new ClockinDao();
            $check = $clockinDao->checkClockinStatus($attendance, $clockset, $clockin);
            $nextType = $check['type'] ?? '';
            $showButton = $check['status'] == Clockin::STATUS_NONE ? false : true;
        }

        $return = [
            'attendance' => $attendance,
            'clockset' => $clockset ?? [],
            'clockin' => $clockin,
            'mac_address' => $mac ?? [],
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
        $day = Carbon::now()->format('Y-m-d');
        $enDay = Carbon::now()->englishDayOfWeek;
        $time = Carbon::now()->format('H:i:s');
        $wifi = $request->getInputWifi();
        $useMac = $request->getInputMacAddress();

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
        if ($clockset) {
            //查看是否例外日期
            $dao->checkIsexceptionDayByDay($attendance, $day) && $clockset = null;
        }
        if (empty($clockset)) {
            return JsonBuilder::Error('今日无需打卡');
        }
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

        $macDao = new UserMacDao();
        $data = [
            'teacher_attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'mac_address' => $request->getInputMacAddress()
        ];

        $result = $macDao->create($data);
        if($result->isSuccess()) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    public function getMonthCount(AttendanceRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new AttendanceDao();
        $monthStart = Carbon::parse($request->getInputMonth())->firstOfMonth();
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
        $countList = $clockinDao->getList($attendance, $monthStart, $monthEnd);

        $return = [
            'attendance' => $attendance,
            'month' => $monthStart->format('Y-m'),
        ];
        return array_merge($return, $countList);
    }

}
