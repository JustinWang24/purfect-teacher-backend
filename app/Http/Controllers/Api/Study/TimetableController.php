<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/27
 * Time: 上午11:11
 */

namespace App\Http\Controllers\Api\Study;


use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Timetable\TimeSlotDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Requests\TimeTable\TimetableRequest;

class TimetableController extends Controller
{
    public function student(TimetableRequest $request) {
        $user = $request->user();
        $date = $request->get('date','2020-02-27');
        // todo 调试把时间写死
        $date = Carbon::parse('2020-02-27');;
        $gradeUser = $user->gradeUser;
        $gradeId = $gradeUser->grade_id;
        $school = $gradeUser->school;
        $schoolConfiguration = $school->configuration;
        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        $year = $schoolConfiguration->getSchoolYear($date->toDateString());
        $term = $schoolConfiguration->guessTerm($date->month);
        $weekdayIndex = $date->weekday();  // 周几
        $weeks = $schoolConfiguration->getAllWeeksOfTerm($term, false);
        $week = $schoolConfiguration->getScheduleWeek($date, $weeks, $term);
//        $weekIndex = $weeks->getScheduleWeekIndex(); // 第几周
        $oddWeek = $schoolConfiguration->getOddWeek($date, $weeks, $term);

        $timetableItemDao = new TimetableItemDao();
        // 获取当天的课程
        $item = $timetableItemDao->getItemsByWeekDayIndex($weekdayIndex,$year,$term,$oddWeek,$gradeId);
        $timeSlotDao = new TimeSlotDao();
        $forStudyingSlots = $timeSlotDao->getAllStudyTimeSlots($school->id);

        $result = [];
        foreach ($forStudyingSlots as $key => $value) {
            $course = [];
            foreach ($item as $k => $val) {
                if($value->id == $val['time_slot_id']) {
                    $course = [
                        'name' => $val['course'],
                        'room' => $val['room'],
                        'teacher' => $val['teacher'],
                        'label' => [],
                    ];
                }

            }
            $result[$key] = [
                'time_slot_id' => $value->id,
                'time_slot_name' => $value->name,
                'from' => $value->from,
                'to' => $value->to,
                'current' => $value->current,
                'course' => $course,
            ];
        }

        return JsonBuilder::Success($result);
    }

}