<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/27
 * Time: 上午11:11
 */

namespace App\Http\Controllers\Api\Study;


use App\Utils\JsonBuilder;
use App\Dao\Schools\SchoolDao;
use App\Utils\Time\CalendarDay;
use App\Dao\Timetable\TimeSlotDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Courses\Lectures\LectureDao;
use App\Http\Requests\TimeTable\TimetableRequest;
use Carbon\Carbon;

class TimetableController extends Controller
{

    /**
     * 学生端课程表
     * @param TimetableRequest $request
     * @return string
     */
    public function student(TimetableRequest $request)
    {
        $user = $request->user();
        $date = $request->getDate();

        $return = $this->timetable($user, $date);
        $item = $return['item'];

        $dao = new LectureDao();
        $forStudyingSlots = $return['forStudyingSlots'];

        $timetable = [];
        foreach ($forStudyingSlots as $key => $value) {
            $course = (object)[];
            foreach ($item as $k => $val) {
                if($value->id == $val['time_slot_id']) {
                    // 查询当前老师在该班级上传的资料
                    $types = $dao->getMaterialTypeByCourseId($val['course_id'],$val['teacher_id'],$val['grade_id']);
                    $label = [];
                    foreach ($types as $v) {
                        $label[] = $v->materialType->name;
                    }
                    $course = [
                        'time_table_id' => $val['id'],
                        'idx' => '', // 课节
                        'name' => $val['course'],
                        'room' => $val['room'],
                        'teacher' => $val['teacher'],
                        'label' => $label,
                    ];
                }

            }

            $timetable[$key] = [
                'time_slot_id' => $value->id,
                'time_slot_name' => $value->name,
                'from' => $value->from,
                'to' => $value->to,
                'current' => $value->current,
                'course' => $course,
            ];
        }
        $result = [
            'date' => $date,
            'week' => $return['week'],
            'week_index' => $return['week_index'],
            'timetable' =>$timetable,
        ];

        return JsonBuilder::Success($result);
    }


    /**
     * 教师端课程表
     * @param TimetableRequest $request
     * @return string
     */
    public function teacher(TimetableRequest $request)
    {
        $user = $request->user();
        $date = $request->getDate();

        $return = $this->timetable($user, $date);
        $item = $return['item'];
        $forStudyingSlots = $return['forStudyingSlots'];

        $timetable = $this->dataProcessing($item, $forStudyingSlots);
        $result = [
            'date' => $date,
            'week' => $return['week'],
            'week_index' => $return['week_index'],
            'timetable' =>$timetable,
        ];

        return JsonBuilder::Success($result);

    }


    /**
     * 获取课程表
     * @param $user
     * @param $date
     * @return array
     */
    public function timetable($user, $date)
    {
        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $schoolConfiguration = $school->configuration;
        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        $year = $schoolConfiguration->getSchoolYear($date->toDateString());
        $term = $schoolConfiguration->guessTerm($date->month);
        $weekdayIndex = $date->dayOfWeekIso;  // 周几
        $weeks = $schoolConfiguration->getAllWeeksOfTerm($term, false);
        $week = $schoolConfiguration->getScheduleWeek($date, $weeks, $term);
        $oddWeek = $schoolConfiguration->getOddWeek($date, $weeks, $term);
        $timetableItemDao = new TimetableItemDao();
        // 获取当天的课程
        // 学生
        if($user->isStudent()) {
            $gradeId = $user->gradeUser->grade_id;
            $item = $timetableItemDao->getItemsByWeekDayIndex($weekdayIndex,$year,$term,$oddWeek,$gradeId);
        } else {
            $item = $timetableItemDao->getItemsByWeekDayIndexForTeacherView($weekdayIndex, $year, $term, $oddWeek, $user->id);
        }

        $timeSlotDao = new TimeSlotDao();
        $forStudyingSlots = $timeSlotDao->getAllStudyTimeSlots($schoolId);


        return [
            'item' => $item,
            'week' => $week->getName(),
            'week_index' => CalendarDay::GetWeekDayIndex($weekdayIndex),
            'forStudyingSlots' => $forStudyingSlots,
        ];
    }


    /**
     * 课程表详情
     * @param TimetableRequest $request
     * @return string
     */
    public function timetableDetails(TimetableRequest $request)
    {
        $timetableId = $request->getTimetableId();
        if(is_null($timetableId)) {
            return JsonBuilder::Error('缺少参数');
        }
        $dao = new TimetableItemDao();
        $info = $dao->getItemById($timetableId);
        if(is_null($info)) {
            return JsonBuilder::Error('该详情不在');
        }
        $timeSlot = $info->timeSlot;
        $course = $info->course;
        $teacher = $info->teacher;
        $grade = $info->grade;
        $room = $info->room;

        $dao = new LectureDao();
        $return = $dao->getMaterialsByCourseIdAndTeacherIdAndGradeId($course->id, $teacher->id, $grade->id);
        $types = $dao->getMaterialType($info->school_id);

        $materials = [];
        foreach ($types as $key => $value) {

            $list = [];
            foreach ($return as $k => $val) {
                if($val->type == $value->type_id) {
                    $list[] = [
                        'material_id' =>$val->id,
                        'idx' => '第'.$val->idx.'节',
                        'desc' => $val->description,
                        'url' => $val->url,
                    ];
                }
            }
            $materials[] = [
                'type_name' => $value->name,
                'list' => $list
            ];
        }

        $result = [
            'time_slot' => $timeSlot->name,
            'from' => $timeSlot->from,
            'to' => $timeSlot->to,
            'course' => $course->name,
            'room' => $room->name,
            'teacher' => $teacher->name,
            'grade' => $grade->name,
            'materials' => $materials,
        ];

        return JsonBuilder::Success($result);
    }


    /**
     * 教师周课程表
     * @param TimetableRequest $request
     * @return string
     */
    public function teacherWeek(TimetableRequest $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $date = $request->getDate();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $schoolConfiguration = $school->configuration;
        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        $year = $schoolConfiguration->getSchoolYear($date->toDateString());
        $term = $schoolConfiguration->guessTerm($date->month);
        $weeks = $schoolConfiguration->getAllWeeksOfTerm($term, false);
        $week = $schoolConfiguration->getScheduleWeek($date, $weeks, $term);
        if(is_null($week)) {
            return JsonBuilder::Success('当前时间没有课程');
        }
        $oddWeek = $schoolConfiguration->getOddWeek($date, $weeks, $term);
        // 当前周的开始时间和结束时间
        $start = Carbon::parse($week->toArray()['start']);
        $end = Carbon::parse($week->toArray()['end']);

        $day = Carbon::parse($end)->diffInDays($start) + 1;
        $timeSlotDao = new TimeSlotDao();
        $forStudyingSlots = $timeSlotDao->getAllStudyTimeSlots($schoolId);


        $timetableItemDao = new TimetableItemDao();
        $result = [];
        // 循环一周
        for ($i=0; $i<$day; $i++) {
            $date = Carbon::parse($start)->addDays($i);
            $weekdayIndex = $date->dayOfWeekIso;  // 周几
            $item = $timetableItemDao->getItemsByWeekDayIndexForTeacherView($weekdayIndex, $year, $term, $oddWeek, $user->id);

            $timetable = $this->dataProcessing($item, $forStudyingSlots);

            $result[] = [
                'date' => $date->toDateString(),
                'week' => $week->getName(),
                'week_index' => CalendarDay::GetWeekDayIndex($weekdayIndex),
                'timetable' => $timetable,
            ];

        }

        return JsonBuilder::Success($result);

    }


    /**
     * 数据处理
     * @param $item
     * @param $forStudyingSlots
     * @return array
     */
    public function dataProcessing($item, $forStudyingSlots)
    {

        $dao = new LectureDao();
        $timetable = [];
        foreach ($item as $key => $value) {
            $time_slot_name = '';
            $from = '';
            $to = '';

            foreach ($forStudyingSlots as $k => $val) {
                if($value['time_slot_id'] == $val->id) {
                    $time_slot_name = $val->name;
                    $from = $val->from;
                    $to = $val->to;
                }
            }

            // 查询当前老师在该班级上传的资料
            $types = $dao->getMaterialTypeByCourseId($value['course_id'],$value['teacher_id'],$value['grade_id']);
            $label = [];
            foreach ($types as $v) {
                $label[] = $v->materialType->name;
            }

            $timetable[] = [
                'time_table_id' => $value['id'],
                'time_slot_id' => $value['time_slot_id'],
                'grade_name' => $value['grade_name'],
                'idx' => '', // 课节
                'room' => $value['room'],
                'course' => $value['course'],
                'time_slot_name' => $time_slot_name,
                'from' => $from,
                'to' => $to,
                'label' => $label,
            ];
        }

        return $timetable;
    }


}