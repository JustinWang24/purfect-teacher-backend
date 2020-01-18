<?php

namespace App\Dao\TeacherAttendance;


use App\Models\TeacherAttendance\Attendance;
use App\Models\TeacherAttendance\Clockin;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;

class ClockinDao
{
    public function create($attendance, $clockset, $clockin, $user, $day, $time, $source = 1)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        $dateTime = Carbon::parse($day . $time)->timestamp;
        if (Carbon::parse($day . $clockset->start)->timestamp > $dateTime || Carbon::parse($day . $clockset->end)->timestamp < $dateTime) {
            $messageBag->setMessage('不在打卡时间段');
            return $messageBag;
        }









        $data = [
            'teacher_attendance_id' => $attendance->id,
            'user_id' => $user->id,
            'day' => $day,
            'time' => $time,
            'type' => $type,
            'status' => $status,
            'source' => $source
        ];
        $result = Clockin::create($data);

        $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
        $messageBag->setData(['id'=>$result->id]);
        return $messageBag;
    }

    public function checkClockinStatus($attendance, $clockset, $clockin, $day, $time)
    {
        $return = [
            'status' => Clockin::STATUS_NONE,
            'type' => null
        ];
        $dateTime = Carbon::parse($day . $time)->timestamp;
        $startTime = Carbon::parse($day . $clockset->start)->timestamp;
        $endTime = Carbon::parse($day . $clockset->end)->timestamp;
        $morningTime = Carbon::parse($day . $clockset->morning)->timestamp;
        $morningLateTime = Carbon::parse($day . $clockset->morning_late)->timestamp;
        $afternoonTime = Carbon::parse($day . $clockset->afternoon)->timestamp;
        $afternoonLateTime = Carbon::parse($day . $clockset->afternoon_late)->timestamp;
        $eveningTime = Carbon::parse($day . $clockset->evening)->timestamp;


        if ($startTime > $dateTime || $endTime < $dateTime) {
            return $return; //不在打卡时间
        }

        //当前打卡阶段
        $clockHas = null;
        $clockin[Clockin::TYPE_MORNING] && $clockHas = Clockin::TYPE_MORNING;
        $clockin[Clockin::TYPE_AFTERNOON] && $clockHas = Clockin::TYPE_AFTERNOON;
        $clockin[Clockin::TYPE_EVENING] && $clockHas = Clockin::TYPE_EVENING;

        if ($attendance->using_afternoon == Attendance::AFTERNOON_USED) {

        }else {
            if (!$clockHas) {
                if ($dateTime < )
            }
        }


    }
}
