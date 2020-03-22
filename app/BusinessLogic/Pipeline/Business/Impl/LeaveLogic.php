<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Business\Impl;
use App\Dao\TeacherAttendance\AttendanceDao;
use App\Models\TeacherAttendance\Clockin;
use App\Models\TeacherAttendance\Clockset;
use App\Models\TeacherAttendance\Leave;
use App\Models\TeacherAttendance\LeaveDetail;
use App\Models\TeacherAttendance\UserMac;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveLogic
{
    public $user;
    public $source;
    public function __construct(User $user, $source)
    {
        $this->user = $user;
        $this->source = $source;
    }

    public function handle($options)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try {
            $dateArr = explode('~', $options['date_date']);
            $start = Carbon::parse(trim($dateArr[0]));
            $end   = Carbon::parse(trim($dateArr[1]));
            $info = Leave::create([
                'user_id' => $this->user->id,
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
                'source' => $this->source
            ]);
            //如果教师已经加入了考勤组 记录一份关于打卡的详情
            $organizationIdArr = $this->user->organizations->pluck('organization_id')->toArray();
            $dao = new AttendanceDao();
            $attendance = $dao->getByOrganizationIdArr($organizationIdArr, $this->user->getSchoolId());
            if (!empty($attendance)) {
                $start = $start->timestamp;
                $end = $end->timestamp;
                DB::beginTransaction();
                while ($start <= $end) {
                    $selectedDay = date('Y-m-d', $start);
                    $selectedStart = isset($selectedStart) ? '00:00:00' : strtotime('H:i:s', $start);
                    $selectedEnd = $selectedDay == date('Y-m-d', $end) ? strtotime('H:i:s', $end) : '23:59:59';
                    $selectedEnday = Carbon::parse($start)->englishDayOfWeek;
                    $clocketSet = $dao->getOnedayClockset($attendance, $selectedEnday);
                    if (strtotime($selectedDay . ' ' . $selectedStart) <= strtotime($selectedDay . ' ' . $clocketSet->morning)
                        && strtotime($selectedDay . ' ' . $selectedEnd >= strtotime($selectedDay. ' ' . $clocketSet->morning))
                    ) {
                        $data = [
                            'leave_id' => $info->id,
                            'teacher_attendance_id' => $attendance->id,
                            'user_id' => $this->user->id,
                            'source' => $this->source,
                            'day' => $selectedDay,
                            'time' => $clocketSet->morning,
                            'type' => Clockin::TYPE_MORNING
                        ];
                        LeaveDetail::create($data);
                    }
                    if ($attendance->using_afternoon
                        && strtotime($selectedDay . ' ' . $selectedStart) <= strtotime($selectedDay . ' ' . $clocketSet->afternoon)
                        && strtotime($selectedDay . ' ' . $selectedEnd >= strtotime($selectedDay. ' ' . $clocketSet->afternoon))
                    ) {
                        $data = [
                            'leave_id' => $info->id,
                            'teacher_attendance_id' => $attendance->id,
                            'user_id' => $this->user->id,
                            'source' => $this->source,
                            'day' => $selectedDay,
                            'time' => $clocketSet->afternoon,
                            'type' => Clockin::TYPE_AFTERNOON
                        ];
                        LeaveDetail::create($data);
                    }

                    if (strtotime($selectedDay . ' ' . $selectedStart) <= strtotime($selectedDay . ' ' . $clocketSet->evening)
                        && strtotime($selectedDay . ' ' . $selectedEnd >= strtotime($selectedDay. ' ' . $clocketSet->evening))
                    ) {
                        $data = [
                            'leave_id' => $info->id,
                            'teacher_attendance_id' => $attendance->id,
                            'user_id' => $this->user->id,
                            'source' => $this->source,
                            'day' => $selectedDay,
                            'time' => $clocketSet->evening,
                            'type' => Clockin::TYPE_EVENING
                        ];
                        LeaveDetail::create($data);
                    }

                    $start += 86400;
                }
                DB::commit();
            }

            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }catch (\Exception $exception) {
            $bag->setMessage( $exception->getMessage());
        }
        return $bag;
    }

}
