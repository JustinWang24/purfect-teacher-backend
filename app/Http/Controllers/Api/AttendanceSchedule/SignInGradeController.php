<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/18
 * Time: 上午11:59
 */

namespace App\Http\Controllers\Api\AttendanceSchedule;


use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\Courses\CourseDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Utils\JsonBuilder;
use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimeSlotDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use Carbon\Carbon;

class SignInGradeController extends Controller
{

    // 课程的班级列表
    public function courseClassList(MyStandardRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();

        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        $now = Carbon::now();
//        $now = Carbon::parse('2020-01-18 14:40:00');
        $weeks = $configuration->getScheduleWeek($now);
        if(is_null($weeks)) {
           return JsonBuilder::Error('当前没有课程');
        }

        $week = $weeks->getScheduleWeekIndex();
        $timeTableItemDao = new TimetableItemDao();
        $return = $timeTableItemDao->getCurrentItemByUser($user);

        if(count($return) == 0) {
            return JsonBuilder::Success('当前没有课程');
        }
        $courseId = $return->pluck('course_id')->toArray()[0];
        $courseDao = new CourseDao();
        $course = $courseDao->getCourseById($courseId);
        $attendancesDao = new AttendancesDao();

        $list = [];
        // 获取签到详情
        foreach ($return as $key => $item) {
            $re = $attendancesDao->getAttendByTimeTableIdAndWeek($item->id, $week);
            if(!is_null($re)) {
                $list[$key]['grade'] = $re->grade->name;
                $list[$key]['total_number'] = $re->total_number;
                $list[$key]['actual_number'] = $re->actual_number;
                $list[$key]['leave_number'] = $re->leave_number;
                $list[$key]['missing_number'] = $re->missing_number;
                $list[$key]['status'] = $re->status;
            }

        }
        $course = [
            'date' =>$now->toDateString(),
            'name'=>$course->name,
            'time_slot'=>$return[0]->timeSlot->name,
            ];

        $data = ['course'=>$course, 'grade_list'=>$list];

        return JsonBuilder::Success($data);

    }

}