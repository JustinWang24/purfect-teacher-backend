<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


use App\Models\Course;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use App\Dao\Courses\CourseMajorDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Dao\AttendanceSchedules\AttendancesLeavesDao;
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
        $month = $request->get('month',Carbon::now()->month);
        $user = $request->user();
        $grade = $user->gradeUser->grade;
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        // 学期
        $term = $configuration->guessTerm($month);
        $weeks = $configuration->getAllWeeksOfTerm($term);

        $timetableDao = new TimetableItemDao();
        $courseMajorDao = new CourseMajorDao();
        $attendancesLeavesDao = new AttendancesLeavesDao();
        $attendancesDetailsDao = new AttendancesDetailsDao();
        $courseList = $courseMajorDao->getCoursesByMajorAndYear($grade->major_id, $grade->gradeYear());
        foreach ($courseList as $key => $val) {

            // 签到次数
            $signNum = $attendancesDetailsDao->getDetailsCountByUser($user->id, $val['id'], $year,$term);
            // 请假次数
            $leavesNum = $attendancesLeavesDao->getLeavesCountByUser($user->id, $val['id'], $year, $term);
            // 课程总次数
            $courseNum = $timetableDao->getCourseCountByCourseId($grade->id, $val['id'], $year, $term, $weeks);

            $courseList[$key]['sign_num'] = $signNum;
            $courseList[$key]['leaves_num'] = $leavesNum;
            $courseList[$key]['truant_num'] = $courseNum - $signNum - $leavesNum;
        }

        return JsonBuilder::Success($courseList);
    }

    // 签到详情
    public function signInDetails(AttendanceRequest $request) {

    }

}
