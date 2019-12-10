<?php

namespace App\Http\Controllers\Api\AttendanceSchedule;

use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceScheduleController extends Controller
{
    /**
     * @param Request $request
     * @param $taskId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new AttendanceSchedulesDao();

        $tasks = $dao->getAllTaskForSchool($schoolId, $cycle='week', $current=0);

        $time       = $dao->getTimes();
        $displayArr = [];
        foreach ($tasks as $key=> $task)
        {

            $taskObj    = $dao->getTaskBySchoolId($task->id, $schoolId);
            $slots      = $taskObj->timeSlots()->get();
            $schedules  = $dao->getSomeoneTaskScheduleForSchool($schoolId, $task->id);
            //拼有序数组
            $data = [];
            foreach ($schedules as $schedule)
            {
                $tmpUsers = $schedule->user()->get();
                foreach ($tmpUsers as $tmpUser)
                {
                    $tmp['userName'] = $tmpUser->name;
                    $tmp['department'] = $tmpUser->department()->first()->name;
                    $tmp['major'] = $tmpUser->major()->first()->name;
                }
                $week = $schedule->week==0?7:$schedule->week;
                $data[$week][$schedule->time_slot_id][] = $tmp;
            }
            for ($i = 1; $i <= 7; $i++) {
                if ((strtotime($time[0]) + ($i - 1) * 86400) >= strtotime($taskObj->start_time) && (strtotime($time[0]) + ($i - 1) * 86400) <= strtotime($taskObj->end_time)) {
                    foreach ($slots as $slot) {
                        $arr = [];
                        if (!empty($data[$i][$slot->id])) {
                            $arr['star_time'] = date('Y-m-d',strtotime($time[0]) + ($i - 1) * 86400) . ' ' . $slot->start_time ;
                            $arr['end_time']  = date('Y-m-d',strtotime($time[0]) + ($i - 1) * 86400) . ' ' . $slot->end_time;
                            foreach ($data[$i][$slot->id] as $k=>$user) {
                                $arr['teacher'][$k]['name']= $user['userName'];
                                $arr['teacher'][$k]['department']=$user['department'];
                                $arr['teacher'][$k]['major']=$user['major'];
                            }
                            $arr['task'] = $taskObj->title;
                            $displayArr[] = $arr;
                        } else {
                            continue;
                        }

                    }

                }
            }
        }
        return JsonBuilder::Success($displayArr);
    }
}
