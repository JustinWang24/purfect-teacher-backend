<?php

namespace App\Dao\TeacherAttendance;

use App\Models\TeacherAttendance\Attendance;
use App\Models\TeacherAttendance\Clockset;
use App\Models\TeacherAttendance\ExceptionDay;
use App\Models\TeacherAttendance\Organization;
use App\Models\Users\UserOrganization;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\Misc\Contracts\Title;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceDao
{
    public function saveClocksets($attendance,$data) {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try{
            foreach ($data as $item) {
                $item['start'] = Carbon::parse($data['start'])->format('H:i:s');
                $item['end'] = Carbon::parse($data['end'])->format('H:i:s');
                $item['morning'] = Carbon::parse($data['morning'])->format('H:i:s');
                $item['morning_late'] = Carbon::parse($data['morning_late'])->format('H:i:s');
                $item['evening'] = Carbon::parse($data['evening'])->format('H:i:s');
                if ($attendance->using_afternoon) {
                    $item['afternoon_start'] = Carbon::parse($data['afternoon_start'])->format('H:i:s');
                    $item['afternoon'] = Carbon::parse($data['afternoon'])->format('H:i:s');
                    $item['afternoon_late'] = Carbon::parse($data['afternoon_late'])->format('H:i:s');
                }else {
                    $item['afternoon_start'] = null;
                    $item['afternoon'] = null;
                    $item['afternoon_late'] = null;
                }

                $clockSet = Clockset::where('teacher_attendance_id', $attendance->id)
                    ->where('week', $item['week'])->first();
                if ($clockSet) {
                    $clockSet->start = $item['start'];
                    $clockSet->end = $item['end'];
                    $clockSet->morning = $item['morning'];
                    $clockSet->morning_late = $item['morning_late'];
                    $clockSet->evening = $item['evening'];
                    $clockSet->afternoon_start = $item['afternoon_start'];
                    $clockSet->afternoon = $item['afternoon'];
                    $clockSet->afternoon_late = $item['afternoon_late'];
                    $clockSet->is_weekday = $item['is_weekday'];
                    $clockSet->save();
                }else {
                    Clockset::create([
                        'teacher_attendance_id' => $attendance->id,
                        'week' => $item['week'],
                        'start' => $item['start'],
                        'end' => $item['end'],
                        'morning' => $item['morning'],
                        'morning_late' => $item['morning_late'],
                        'evening' => $item['evening'],
                        'afternoon_start' => $item['afternoon_start'],
                        'afternoon' => $item['afternoon'],
                        'afternoon_late' => $item['afternoon_late'],
                        'is_weekday' => $item['is_weekday']
                    ]);
                }
            }

            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($attendance);
            return $bag;
        }catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
            return $bag;
        }
    }
    public function create($data, $organizations) {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try{
            $attendance = Attendance::create($data);
            //创建组织
            foreach ($organizations as $organization) {
                Organization::create([
                    'teacher_attendance_id' => $attendance->id,
                    'organization_id' => end($organization)
                ]);
            }
            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($attendance);
            return $bag;
        }catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
            return $bag;
        }
    }
    public function update($data, $organizations) {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try{
            $attendance = $this->getById($data['id']);
            $attendance->title = $data['title'];
            $attendance->wifi_name = $data['wifi_name'];
            $attendance->using_afternoon = $data['using_afternoon'];
            $attendance->save();
            //更新组织
            Organization::where('teacher_attendance_id', $attendance->id)->delete();
            foreach ($organizations as $organization) {
                Organization::create([
                    'teacher_attendance_id' => $attendance->id,
                    'organization_id' => end($organization)
                ]);
            }
            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($attendance);
            return $bag;
        }catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
            return $bag;
        }
    }
    public function getById($id) {
        return Attendance::with('clocksets')
            ->with('exceptiondays')
            ->with('organizations')
            ->where('id', $id)->first();
    }
    public function deleteExceptionday($id) {
        return ExceptionDay::where('id', $id)->delete();
    }

    public function getExceptiondayByday($attendanceId, Carbon $day) {
        return ExceptionDay::where('teacher_attendance_id', $attendanceId)->where('day' , $day->format('Y-m-d'))->first();
    }
    public function saveExceptionday($attendanceId,Carbon $day) {
        return ExceptionDay::create([
            'teacher_attendance_id' => $attendanceId,
            'day' => $day->format('Y-m-d')
        ]);
    }

    /**
     * 分页获取考勤配置
     * @param $schoolId
     * @return mixed
     */
    public function getPaginated($schoolId) {
        $list = Attendance::where('school_id', $schoolId)
            ->with('organizations')
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        foreach ($list as &$item) {
            $item->members = UserOrganization::whereIn('organization_id', $item->organizations()->pluck('organization_id')->toArray())
                ->where('title_id', '=', Title::MEMBER)->count();
        }
        return $list;
    }
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
