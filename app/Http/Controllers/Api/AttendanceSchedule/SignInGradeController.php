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



        $timeTableItemDao = new TimetableItemDao();
        $return = $timeTableItemDao->getCurrentItemByUser($user);
        if(count($return) == 0) {
            return JsonBuilder::Success('当前没有课程');
        }
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        $week = $configuration->getScheduleWeek(Carbon::now())->getScheduleWeekIndex();
        $courseId = $return->pluck('course_id')->toArray()[0];
        $courseDao = new CourseDao();
        $course = $courseDao->getCourseById($courseId);
        $attendancesDao = new AttendancesDao();
        // 获取签到详情
        foreach ($return as $key => $item) {

        }
        dd($course);
    }

}