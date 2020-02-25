<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/6
 * Time: 下午1:52
 */

namespace App\Http\Controllers\Api\Study;


use App\Dao\ElectiveCourses\TeacherApplyElectiveCourseDao;
use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Schools\SchoolDao;
use App\Http\Controllers\Controller;
use App\Models\Courses\CourseMaterial;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Requests\MyStandardRequest;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;

class IndexController extends Controller
{
    public function index(MyStandardRequest $request) {
        $user = $request->user();

        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        // todo 时间暂时写死
        $date = Carbon::now()->toDateString();
        $date = Carbon::parse('2020-01-08 14:40:00');;
        $year = $configuration->getSchoolYear($date);
        $month = Carbon::parse($date)->month;
        $term = $configuration->guessTerm($month);
        $timetableItemDao = new TimetableItemDao();
        $item = $timetableItemDao->getCurrentItemByUser($user);


        $teacherApplyElectiveDao = new TeacherApplyElectiveCourseDao();
        $electiveTime = $teacherApplyElectiveDao->getElectiveCourseStartAndEndTime($schoolId, $term);
        $electiveStart = Carbon::parse($electiveTime[0]);
        $electiveEnd = Carbon::parse($electiveTime[1]);
        $selectCourse = [
            'status' => $electiveStart->timestamp < $date->timestamp && $electiveEnd->timestamp > $date->timestamp, // true 开启 false 关闭
            'time' => $electiveStart->format('Y年m月d日') . '-' . $electiveEnd->format('Y年m月d日'),
            'msg' => '大家选课期间请看好选课程对应的学分',
        ];

        $timetable = (object)[];
        $attendancesDetailsDao = new AttendancesDetailsDao();

        $signIn = [
            'status' => 0,
            'signIn_num' => $attendancesDetailsDao->getSignInCountByUser($user->id, $year, $term),
            'leave_num' => $attendancesDetailsDao->getLeaveCountByUser($user->id, $year, $term),
            'truant_num' => $attendancesDetailsDao->getTruantCountByUser($user->id, $year, $term),
        ];


        $evaluateTeacher = false;
        if(!is_null($item)) {

            $weeks = $configuration->getScheduleWeek(Carbon::parse($date), null, $term);
            $week = $weeks->getScheduleWeekIndex();

            $course = $item->course;
            $materials = $course->materials;
            $types = array_column($materials->toArray(), 'type');
            $label = [];
            foreach ($types as $key => $val) {
                $label[] = CourseMaterial::GetTypeText($val);
            }

            $timetable = [
                'time_slot' => $item->timeSlot->name,
                'time' => $item->timeSlot->from.'-'.$item->timeSlot->to,
                'label' => $label,
                'course' => $course->name,
                'room' => $item->room->name,
                'teacher' => $item->teacher->name,
                'week' => $week,
                'item_id'=> $item->id,
            ];

            $weeks = $configuration->getScheduleWeek(Carbon::parse($date), null, $term);

            $week = $weeks->getScheduleWeekIndex() ?? '';

            $attendancesDao = new AttendancesDao();

            $attendance = $attendancesDao->getAttendanceByTimeTableId($item->id,$week);


            $detail = $attendancesDetailsDao->getDetailByUserId($user->id, $attendance->id);

            $signIn['status'] = $detail->mold ?? 0;
            $evaluateTeacher = true;
        }

        $studyData = '';
        $data = [
            'selectCourse' => $selectCourse, // 选课
            'timetable' => $timetable,  // 课程
            'studyData' => $studyData, // 学习资料
            'signIn'=> $signIn, // 签到
            'evaluateTeacher' => $evaluateTeacher // 评教 true false
        ];
        return JsonBuilder::Success($data);
    }

}
