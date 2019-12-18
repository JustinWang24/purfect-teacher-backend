<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


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

        $courseMajorDao = new CourseMajorDao();
        $courseList = $courseMajorDao->getCoursesByMajorAndYear($grade->major_id, $grade->gradeYear());
        $attendancesDetailsDao = new AttendancesDetailsDao();
        $attendancesLeavesDao = new AttendancesLeavesDao();
        foreach ($courseList as $key => $val) {
            $signNum = $attendancesDetailsDao->getDetailsCountByUser($user->id, $val['id'], $year,$term);
            $leavesNum = $attendancesLeavesDao->getLeavesCountByUser($user->id, $val['id'], $year, $term);
            $courseList[$key]['sign_num'] = $signNum;
            $courseList[$key]['leaves_num'] = $leavesNum;
        }

        dd($courseList);
    }

}
