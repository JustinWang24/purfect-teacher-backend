<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/18
 * Time: 上午11:59
 */

namespace App\Http\Controllers\Api\AttendanceSchedule;


use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Schools\SchoolDao;
use App\Dao\Courses\CourseDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Requests\MyStandardRequest;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Http\Requests\AttendanceSchedule\AttendanceRequest;

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
        $now = Carbon::parse('2020-01-18 14:40:00');
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
                $list[$key]['attendance_id'] = $re->id;
                $list[$key]['grade_id'] = $re->grade->id;
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


    /**
     * 课程签到详情
     * @param AttendanceRequest $request
     * @return string
     */
    public function signDetails(AttendanceRequest $request) {
        $attendanceId = $request->getAttendanceId();
        $gradeId = $request->getGradeId();
        $gradeDao = new GradeUserDao();

        $gradeUser = $gradeDao->getGradeUserByGradeId($gradeId);
        $total = count($gradeUser);

        $dao = new AttendancesDetailsDao();
        $list = $dao->getAttendDetailsByAttendanceId($attendanceId);
        $userIds = $list->pluck('student_id')->toArray();
        $student = [];
        $signin = 0;
        $leave = 0;
        $score = []; // 评分列表
        foreach ($gradeUser as $key => $item) {
            foreach ($list as $k => $v) {
                $score[$key]['user_id'] = $item->user_id;
                $score[$key]['name'] = $item->name;
                $student[$key]['user_id'] = $item->user_id;
                $student[$key]['name'] = $item->name;
                if(in_array($item->user_id, $userIds)) {
                    if($v->mold == AttendancesDetail::MOLD_SIGN_IN) {
                        $signin += 1;
                    }
                    if($v->mold == AttendancesDetail::MOLD_LEAVE) {
                        $leave += 1;
                    }
                    $student[$key]['mold'] = $v->mold;
                    $score[$key]['score'] = $v->score;
                } else {
                    $student[$key]['mold'] = 0;  // 未签到
                    $score[$key]['score'] = 0;
                }
            }

        }
        // 未签到
        $unSign = $total - $signin - $leave;

        $data = [
            'stat' => ['total'=>$total, 'signin'=>$signin, 'leave'=>$leave, 'un_sign'=>$unSign],
            'signin' => $student,
            'score' => $score,
        ];
        return JsonBuilder::Success($data);
    }


    /**
     * 编辑课程签到详情
     * @param AttendanceRequest $request
     * @return string
     */
    public function saveDetails(AttendanceRequest $request) {
        $dao = new AttendancesDetailsDao();
        $attendanceId = $request->getAttendanceId();
        $details = $request->get('details');
        $result = $dao->saveDetails($attendanceId, $details);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


}