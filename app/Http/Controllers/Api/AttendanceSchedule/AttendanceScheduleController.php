<?php

namespace App\Http\Controllers\Api\AttendanceSchedule;

use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\Schools\SchoolDao;
use App\Models\Schools\SchoolConfiguration;
use App\Utils\JsonBuilder;
use App\Utils\Time\CalendarWeek;
use Carbon\Carbon;
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

        $current =  $request->input('week', 0);;
        $tasks = $dao->getAllTaskForSchool($schoolId, 'week', $current);

        $time       = $dao->getTimes($current);
        $time1       = $dao->getTimes($current,'month');
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
                    $tmp['mobile'] = $tmpUser->user()->first()->mobile;
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
                                $arr['teacher'][$k]['mobile']=$user['mobile'];
                            }
                            $arr['task'] = $taskObj->title;
                            $arr['detail'] = $taskObj->content;
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
    public function detail(Request $request, $schedultId)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new AttendanceSchedulesDao();
    }

    /**
     * 获取给定日期的值周安排
     * @param Request $request
     * @return string
     */
    public function load_special(Request $request){
        $dao = new AttendanceSchedulesDao();

        $schoolId = $request->get('school_id', null);
        if($schoolId){
            $schoolId = $request->user('api')->getSchoolId();
        }

        $specials = $dao->getSpecialAttendances($schoolId);

        /**
         * @var SchoolConfiguration $cofig
         */
        $config = (new SchoolDao())->getSchoolById($schoolId)->configuration;
        $weeks = $config->getAllWeeksOfTerm();
        $sp = null;

        $theDate = $request->get('date');
        $theDateCarbon = Carbon::createFromFormat('Y-m-d',$theDate);

        foreach ($specials as $special) {

            if($special->start_date->format('Y-m-d') <= $theDate && $theDate <= $special->end_date->format('Y-m-d')){
                $special->grade;
                foreach ($weeks as $week) {
                    /**
                     * @var CalendarWeek $week
                     */
                    if($week->includes($theDateCarbon)){
                        $special->week_name = $week->getName();
                        break;
                    }
                }
                $sp = $special;
                break;
            }
        }
        return JsonBuilder::Success(['special'=>$sp]);
    }
}
