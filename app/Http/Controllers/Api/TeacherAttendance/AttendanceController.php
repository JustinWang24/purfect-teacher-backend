<?php

namespace App\Http\Controllers\Api\TeacherAttendance;

use App\Dao\TeacherAttendance\AttendanceDao;
use App\Dao\TeacherAttendance\ClockinDao;
use App\Dao\TeacherAttendance\UserMacDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherAttendance\AttendanceRequest;
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
        //获取今日配置
        $clockset = $dao->getOnedayClockset($attendance, $enDay);
        if ($clockset) {
            //查看是否例外日期
            $dao->checkIsexceptionDayByDay($attendance, $day) && $clockset = null;
        }
        //获取今日记录
        $clockin = $dao->getOnedayClockin($attendance, $day, $user->id);
        //重组下数据
        $clockRet = [
            'morning' => [],
            'afternoon' => [],
            'evening' => []
        ];
        foreach ($clockin as $in) {
            $clockRet[$in->type] = $in;
        }
        //获取Mac
        $mac = $dao->getMacAddress($attendance, $user->id);

        $return = [
            'attendance' => $attendance,
            'clockset' => $clockset ?? [],
            'clockin' => $clockRet,
            'mac_address' => $mac ?? [],
            'date' => $day
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
        $wifi = $request->get('wifi');

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

        //获取今日记录
        $clockin = $dao->getOnedayClockin($attendance, $day, $user->id);
        //重组下数据
        $clockRet = [
            'morning' => [],
            'afternoon' => [],
            'evening' => []
        ];
        foreach ($clockin as $in) {
            $clockRet[$in->type] = $in;
        }

        $clockinDao = new ClockinDao();
        $result = $clockinDao->create($attendance, $clockset, $clockRet, $user, $day, $time);
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

}
