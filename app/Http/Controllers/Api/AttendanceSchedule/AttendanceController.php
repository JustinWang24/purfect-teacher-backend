<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Courses\CourseMajorDao;
use App\Http\Controllers\Controller;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Http\Requests\AttendanceSchedule\AttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * 课程签到列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function signInRecord(AttendanceRequest $request) {

        // 学年
        $year = $request->get('year',Carbon::now()->year);
        $user = $request->user();
        $grade = $user->gradeUser->grade;
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        // 学期
        $term = $request->get('term',$configuration->guessTerm(Carbon::now()->month));

        $courseMajorDao = new CourseMajorDao();
        $attendancesDetailsDao = new AttendancesDetailsDao();
        $courseList = $courseMajorDao->getCoursesByMajorAndYear($grade->major_id, $grade->gradeYear());
        foreach ($courseList as $key => $val) {

            // 签到次数
            $signNum = $attendancesDetailsDao->getSignInCountByUser($user->id, $val['id'], $year,$term);
            // 请假次数
            $leavesNum = $attendancesDetailsDao->getLeaveCountByUser($user->id, $val['id'], $year, $term);
            // 旷课次数
            $truantNum = $attendancesDetailsDao->getTruantCountByUser($user->id, $val['id'], $year, $term);
            $courseList[$key]['sign_num'] = $signNum;
            $courseList[$key]['leaves_num'] = $leavesNum;
            $courseList[$key]['truant_num'] = $truantNum;
        }

        return JsonBuilder::Success($courseList);
    }


    /**
     * 签到详情列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function signInDetails(AttendanceRequest $request) {

        $courseId = $request->get('course_id');
        if(empty($courseId)) {
            return JsonBuilder::Error('缺少参数');
        }

        $user = $request->user();
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        $year = $request->get('year',Carbon::now()->year);
        // 学期
        $term = $request->get('term',$configuration->guessTerm(Carbon::now()->month));



        $attendancesDetailsDao = new AttendancesDetailsDao();
        $signInList = $attendancesDetailsDao->signInList($year, $user->id, $courseId, $term);
        foreach ($signInList as $key => $val) {
            $signInList[$key]['time_slots'] = $val->timetable->timeSlot->name;
            $signInList[$key]['weekday_index'] = $val->timetable->weekday_index;
            $signInList[$key]['data'] = Carbon::parse($val->created_at)->format('Y-m-d');
            $signInList[$key]['time'] = Carbon::parse($val->created_at)->format('H:i');

            // 判断请假的
            if($val['mold'] == AttendancesDetail::MOLD_LEAVE) {
                if($val['status'] !== AttendancesDetail::STATUS_CONSENT) {
                    unset($signInList[$key]);
                }
            }
            unset($val->status);
            unset($val->timetable);
            unset($val->timetable_id);
            unset($val->created_at);
        }
        $data = array_merge($signInList->toArray());
        return JsonBuilder::Success($data);
    }


    // 添加旷课记录
    public function addTruantRecord(AttendanceRequest $request) {

        $truant = $request->getTruantData();
//        dd($truant);
        $timeTableDao = new TimetableItemDao();
        $timetableInfo = $timeTableDao->getItemById($truant['timetable_id']);
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($timetableInfo->school_id);
        $data = Carbon::parse($truant['date']);
        $week = $school->configuration->getScheduleWeek($data)->getScheduleWeekIndex();;
        $attendanceDao = new AttendancesDao();
//        $attendanceInfo = $attendanceDao->getAttendanceByTimeTableId()
        dd($week);
        $truant['course_id'] = $timetableInfo->course_id;
        $truant['school_id'] = $timetableInfo->school_id;

    }

}
