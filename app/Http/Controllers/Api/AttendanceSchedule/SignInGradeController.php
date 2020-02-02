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
use App\Dao\Schools\GradeDao;
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
                $list[] = [
                    'attendance_id' => $re->id,
                    'grade_id' => $re->grade->id,
                    'grade' => $re->grade->name,
                    'total_number' => $re->total_number,
                    'actual_number' => $re->actual_number,
                    'leave_number' => $re->leave_number,
                    'missing_number' => $re->missing_number,
                    'status' => $re->status,
                ];
            }

        }
        $course = [
            'date' =>$now->toDateString(),
            'name'=>$course->name,
            'time_slot'=>$return[0]->timeSlot->name,
            'room'=>$return[0]->room->name??""
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

        $score = []; // 评分列表
        foreach ($gradeUser as $key => $item) {
            $student[$key]['user_id'] = $item->user_id;
            $student[$key]['name'] = $item->name;
            $score[$key]['user_id'] = $item->user_id;
            $score[$key]['name'] = $item->name;
            $student[$key]['mold'] = 0;  // 未签到
            $score[$key]['score'] = 0;
            foreach ($list as $k => $v) {
                if(in_array($item->user_id, $userIds)) {
                    $student[$key]['mold'] = $v->mold;
                    $score[$key]['score'] = $v->score;
                }
            }
        }


        // 未签到
        $mold = $list->pluck('mold')->toArray();
        $count = array_count_values($mold);
        $signin = $count[AttendancesDetail::MOLD_SIGN_IN] ?? 0;

        $leave = $count[AttendancesDetail::MOLD_LEAVE] ?? 0;
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


    /**
     * 保存评分
     * @param AttendanceRequest $request
     * @return string
     */
    public function saveScore(AttendanceRequest $request) {
        $dao = new AttendancesDetailsDao();
        $attendanceId = $request->getAttendanceId();
        $score = $request->get('score');
        $result = $dao->saveScore($attendanceId, $score);
        $msg = $result->getMessage();
        if($result->isSuccess()) {
            return JsonBuilder::Success($msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }


    /**
     * 课程班级签到列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function signInCourses(AttendanceRequest $request) {
        $userId = $request->user()->id;
        $year = $request->getYear();
        $term = $request->getTerm();
        if(empty($year) || empty($term)) {
            return JsonBuilder::Error('缺少参数');
        }
        $dao = new AttendancesDao();
        $courses = $dao->getSignInCoursesByYearAndTerm($userId, $year, $term);
        $result = [];
        foreach ($courses as $key => $item) {
            $grades = $dao->getSignInGradesByCourseIdAndYearTerm($userId, $item->course_id, $year, $term);
            $result[$key]['course_id'] = $item->course->id;
            $result[$key]['name'] = $item->course->name;
            foreach ($grades as $k => $val) {
                $result[$key]['grade'][$k]['grade_id'] = $val->grade->id;
                $result[$key]['grade'][$k]['name'] = $val->grade->name;
            }
        }
        return JsonBuilder::Success($result);
    }


    /**
     * @param AttendanceRequest $request
     * @return string
     */
    public function signInStudentList(AttendanceRequest $request) {
        $courseId = $request->getCourseId();
        $gradeId = $request->getGradeId();
        $year = $request->getYear();
        $term = $request->getTerm();
        if(empty($courseId) || empty($gradeId) || empty($year) || empty($term)) {
            return JsonBuilder::Error('缺少参数');
        }
        $gradeUserDao = new GradeUserDao();
        $users = $gradeUserDao->getByGradeForApp($gradeId);
        $dao = new AttendancesDetailsDao();

        $students = [];
        foreach ($users as $key => $item) {
            $detail = $dao->getSignInByYearTerm($item->user_id, $year, $term);
            $mold = $detail->pluck('mold')->toArray();
            $scores = $detail->pluck('score')->toArray();
            if(count($scores) == 0) {
                $score = 0;
            } else {
                $score = round(array_sum($scores) / count($scores), 2);
            }

            $remarks = $detail->pluck('remark');
            $status = 0;
            foreach ($remarks as $k => $val) {
                if(!is_null($val)) {
                    $status = 1; break;
                }
            }

            $count = array_count_values($mold);
            $students[$key]['user_id'] = $item->user_id;
            $students[$key]['name'] = $item->name;
            $students[$key]['sign_in'] = $count[AttendancesDetail::MOLD_SIGN_IN] ?? 0;
            $students[$key]['leave'] = $count[AttendancesDetail::MOLD_LEAVE] ?? 0;
            $students[$key]['truant'] = $count[AttendancesDetail::MOLD_TRUANT] ?? 0;
            $students[$key]['score'] = $score;
            $students[$key]['status'] = $status;   // 是否有备注的状态 0:没有备注 1:有备注
        }

        $gradeDao = new GradeDao();
        $grade = $gradeDao->getGradeById($gradeId);
        $courseDao = new CourseDao();
        $course = $courseDao->getCourseById($courseId);

        $data = [
            'grade'=>['id'=>$grade->id, 'name'=>$grade->name],
            'course'=>['id'=>$course->id, 'name'=>$course->name],
            'students'=>$students
        ];

        return JsonBuilder::Success($data);
    }


    /**
     * 备注列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function remarkList(AttendanceRequest $request) {
        $year = $request->getYear();
        $term = $request->getTerm();
        $userId = $request->get('user_id');
        $courseId = $request->getCourseId();
        if(empty($year) || empty($term) || empty($userId)) {
            return JsonBuilder::Error('缺少参数');
        }

        $dao = new AttendancesDetailsDao();
        $return = $dao->getRemarkList($userId, $courseId, $year, $term);

        $list = [];
        foreach ($return as $key => $item) {
            $list[$key]['time'] = $item->created_at->toDateString();
            $list[$key]['weekday_index'] = $item->timetable->weekday_index;
            $list[$key]['timeSlot'] = $item->timetable->timeSlot->name;
            $list[$key]['remark'] = $item->remark;
        }

        $data = [
            'currentPage' => $return->currentPage(),
            'lastPage'    => $return->lastPage(),
            'total'       => $return->total(),
            'list'        => $list
        ];
        return JsonBuilder::Success($data);
    }








}