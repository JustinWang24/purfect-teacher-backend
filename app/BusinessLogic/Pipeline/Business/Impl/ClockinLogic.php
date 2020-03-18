<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Flow\Business\Impl;
use App\Dao\TeacherAttendance\ClockinDao;
use App\Models\TeacherAttendance\Clockin;
use App\Models\TeacherAttendance\Clockset;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;

class ClockinLogic
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle($options)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try {
            $week = Carbon::parse($options['day'])->englishDayOfWeek;
            $time = Clockset::where(['teacher_attendance_id' => $options['attendance_id'], 'week' => $week])->value($options
            ['type']);
            $clockinDao = new ClockinDao();
            $data = [
                'teacher_attendance_id' => $options['attendance_id'],
                'user_id' => $user->id,
                'day' => $options['day'],
                'time' => $time,
                'type' => $options['type'],
                'status' => Clockin::STATUS_NORMAL,
                'source' => Clockin::SOURCE_APPLY
            ];
            $clockinDao->create($data);
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }catch (\Exception $exception) {
            $bag->setMessage( $exception->getMessage());
        }
        return $bag;
    }

}
