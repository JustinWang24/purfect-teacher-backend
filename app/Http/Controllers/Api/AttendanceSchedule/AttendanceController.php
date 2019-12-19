<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


use App\Dao\Timetable\TimetableItemDao;
use Carbon\Carbon;
use App\Dao\Courses\CourseMajorDao;
use App\Http\Controllers\Controller;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Dao\AttendanceSchedules\AttendancesLeavesDao;
use App\Http\Requests\AttendanceSchedule\AttendanceRequest;

class AttendanceController extends Controller
{

    /**
     * @param AttendanceRequest $request
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
//        dd($weeks);

        $courseMajorDao = new CourseMajorDao();
        $courseList = $courseMajorDao->getCoursesByMajorAndYear($grade->major_id, $grade->gradeYear());
        $attendancesDetailsDao = new AttendancesDetailsDao();
        $attendancesLeavesDao = new AttendancesLeavesDao();
        $timetableDao = new TimetableItemDao();
        foreach ($courseList as $key => $val) {
            $signNum = $attendancesDetailsDao->getDetailsCountByUser($user->id, $val['id'], $year,$term);
            $leavesNum = $attendancesLeavesDao->getLeavesCountByUser($user->id, $val['id'], $year, $term);
            $courseNum = $timetableDao->getCourseCountByCourseId($grade->id, $val['id'], $year, $term, $weeks);
            dd($courseNum->toArray());
            $courseList[$key]['sign_num'] = $signNum;
            $courseList[$key]['leaves_num'] = $leavesNum;
        }

        dd($courseList);
    }

}
