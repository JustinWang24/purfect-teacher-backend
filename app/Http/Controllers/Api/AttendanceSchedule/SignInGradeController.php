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
use App\Utils\Time\CalendarDay;
use App\Dao\Users\GradeUserDao;
use App\Utils\Time\GradeAndYearUtil;
use App\Dao\Schools\GradeManagerDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Requests\MyStandardRequest;
use App\Models\Schools\SchoolConfiguration;
use App\Models\AttendanceSchedules\Attendance;
use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Dao\AttendanceSchedules\AttendancesDetailsDao;
use App\Http\Requests\AttendanceSchedule\AttendanceRequest;

class SignInGradeController extends Controller
{


    /**
     * 全部记录的筛选
     * @return string
     */
    public function timeScreen() {
        $year = Carbon::now()->year;
        $lastYear = $year - 1;
        $data = [
            [
                'name'=>$lastYear.'学年第一学期',
                'year'=>$lastYear,
                'term'=>SchoolConfiguration::LAST_TERM,
            ],
            [
                'name'=>$lastYear.'学年第二学期',
                'year'=>$lastYear,
                'term'=>SchoolConfiguration::NEXT_TERM,
            ],
            [
                'name'=>$year.'学年第一学期',
                'year'=>$year,
                'term'=>SchoolConfiguration::LAST_TERM,
            ],
            [
                'name'=>$year.'学年第一学期',
                'year'=>$year,
                'term'=>SchoolConfiguration::NEXT_TERM,
            ],
        ];

        return JsonBuilder::Success($data);
    }




    /**
     * 课程的班级列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function courseClassList(MyStandardRequest $request) {

        $user = $request->user();
        $schoolId = $user->getSchoolId();

        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
        $month = $now->month;
        $term = $configuration->guessTerm($month);
        $weeks = $configuration->getScheduleWeek($now, null, $term);
        if(is_null($weeks)) {
           return JsonBuilder::Success('当前没有课程');
        }

        $week = $weeks->getScheduleWeekIndex();
        $timeTableItemDao = new TimetableItemDao();
        $return = $timeTableItemDao->getCurrentItemByUser($user);
        if(is_null($return) || count($return) == 0 ) {
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
        $molds = array_column($list->toArray(),'mold','student_id');
        $scores = array_column($list->toArray(),'score','student_id');
        $student = [];
        $score = [];  // 评分列表
        foreach ($gradeUser as $key => $item) {
            $student[$key]['user_id'] = $item->user_id;
            $student[$key]['name'] = $item->name;
            $score[$key]['user_id'] = $item->user_id;
            $score[$key]['name'] = $item->name;

            if(array_key_exists($item->user_id,$molds)) {
                $student[$key]['mold'] = $molds[$item->user_id];
                $score[$key]['score'] = $scores[$item->user_id];
            } else {
                $student[$key]['mold'] = 0;  // 未签到
                $score[$key]['score'] = 0;
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
     * 班级内学生签到列表
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


    /**
     * 班主任的班级列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function gradeList(AttendanceRequest $request) {
        $userId = $request->user()->id;
        $dao = new GradeManagerDao();
        $return = $dao->getGradeManagerByAdviserId($userId);
        if(empty($return)) {
            return JsonBuilder::Error('您不是班主任');
        }
        $result = [];
        foreach ($return as $key => $item) {
            $result[] = [
                'grade_id' => $item->grade_id,
                'grade_name' => $item->grade->name
            ];
        }

        return JsonBuilder::Success($result);

    }




    /**
     * 班级签到首页接口
     * @param AttendanceRequest $request
     * @return string
     */
    public function gradeSignIn(AttendanceRequest $request) {
        $user = $request->user();
        $gradeManagerDao = new GradeManagerDao();
        $grades = $gradeManagerDao->getGradeManagerByAdviserId($user->id);

        if(count($grades) == 0) {
            return JsonBuilder::Error('您不是班主任');
        }

        $gradeId = $request->getGradeId();
        if(empty($gradeId)) {
            $gradeId = $grades[0]->grade_id;
        }
        //时间

        $date = $request->get('date',Carbon::now()->toDateString());
        $type = $request->get('type', 1); // 类型：1当天数据 2:历史数据
        $time = Carbon::now()->toTimeString();
        $month = Carbon::parse($date)->month;
        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        $year = $configuration->getSchoolYear($date);
        $term = $configuration->guessTerm($month);
        $weekDay = Carbon::parse($date)->weekday();

        $weeks = $configuration->getScheduleWeek(Carbon::parse($date), null, $term);
        if(is_null($weeks)) {
            return JsonBuilder::Error('当前没有课程');
        }

        $week = $weeks->getScheduleWeekIndex();


        // 查询当前时间这个班上的课
        $timeTableItemDao = new TimetableItemDao();
        $return = $timeTableItemDao->getTimetableItemByTime($schoolId, $year, $term, $time, $user->id, $gradeId, $weekDay, $type);

        $weekdayIndex = CalendarDay::GetWeekDayIndex($weekDay);

        $attendancesDao = new AttendancesDao();
        $list = [];
        foreach ($return as $key => $item) {
            $attendance = $attendancesDao->getAttendanceByTimeTableId($item->id, $week);
            $list[$key]['slot_name'] = $item->name;
            $list[$key]['attendance_id'] = $attendance->id;
            $list[$key]['actual_number'] = $attendance->actual_number;
            $list[$key]['leave_number'] = $attendance->leave_number;
            $list[$key]['missing_number'] = $attendance->missing_number;
        }
        $gradeDao = new GradeDao;
        $grade = $gradeDao->getGradeById($gradeId);
        $data = [
            'date' => $date,
            'weekday_index' => $weekdayIndex,
            'grade_name' => $grade->name,
            'list' => $list
        ];
        return JsonBuilder::Success($data);
    }


    /**
     * 班级签到详情
     * @param AttendanceRequest $request
     * @return string
     */
    public function gradeSignInDetails(AttendanceRequest $request) {
        $attendanceId = $request->getAttendanceId();
        $dao = new AttendancesDao();
        $attendance = $dao->getAttendanceById($attendanceId);
        $detailsDao = new AttendancesDetailsDao();
        $details = $detailsDao->getAttendDetailsByAttendanceId($attendanceId);
        // 签到状态
        $molds = array_column($details->toArray(), 'mold', 'student_id');
        $createdAts = array_column($details->toArray(), 'created_at', 'student_id');

        $gradeUserDao = new GradeUserDao();
        $return = $gradeUserDao->getGradeUserPageGradeId($attendance->grade_id);

        $students = $return->getCollection();
        $list = [];
        foreach ($students as $key => $value) {
            $list[$key]['user_id'] = $value->user_id;
            $list[$key]['name'] = $value->name;
            $list[$key]['mold'] = AttendancesDetail::MOLD_TRUANT;
            $list[$key]['created_at'] = '';

            if(array_key_exists($value->user_id,$molds)) {
                $list[$key]['mold'] = $molds[$value->user_id];
                if($molds[$value->user_id] != AttendancesDetail::MOLD_TRUANT) {
                    $createAt = $createdAts[$value->user_id];
                    $list[$key]['created_at'] = Carbon::parse($createAt)->format('Y-m-d H:i');
                }
            }

        }

        $data = [
            'currentPage' => $return->currentPage(),
            'lastPage'    => $return->lastPage(),
            'total'       => $return->total(),
            'list'        => $list
        ];

        return JsonBuilder::Success($data);
    }



    /**
     * 今日评分 或查看历史评分
     * @param AttendanceRequest $request
     * @return string
     */
    public function todayGrade(AttendanceRequest $request) {
        $user = $request->user();
        $dao = new GradeManagerDao();
        $grades = $dao->getGradeManagerByAdviserId($user->id);
        if(empty($grades)) {
            return JsonBuilder::Error('您不是班主任');
        }

        $gradeId = $request->getGradeId();
        if(empty($gradeId)) {
            $gradeId = $grades[0]->grade_id;
        }

        //时间
        $date = $request->get('date',Carbon::now()->toDateString());
        $type = $request->get('type', 1); // 类型：1当天数据 2:历史数据
        $time = Carbon::now()->toTimeString();
        $month = Carbon::parse($date)->month;
        $schoolId = $user->getSchoolId();
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $configuration = $school->configuration;
        $year = $configuration->getSchoolYear($date);
        $term = $configuration->guessTerm($month);
        $weekDay = Carbon::parse($date)->weekDay();
        $weeks = $configuration->getScheduleWeek(Carbon::parse($date), null, $term);
        if(is_null($weeks)) {
            return JsonBuilder::Error('当前没有课程');
        }

        $week = $weeks->getScheduleWeekIndex();
        $weekdayIndex = CalendarDay::GetWeekDayIndex($weekDay);

        // 查询当前时间这个班上的课
        $timeTableItemDao = new TimetableItemDao();
        $return = $timeTableItemDao->getTimetableItemByTime($schoolId, $year, $term, $time, $user->id, $gradeId, $weekDay, $type);

        $attendancesDao = new AttendancesDao();
        $list = [];
        foreach ($return as $key => $item) {
            $attendance = $attendancesDao->getAttendanceByTimeTableId($item->id, $week);
            $list[] = [
                'attendance_id' => $attendance->id,
                'slot_name' => $item->name,
                'course_name' => $item->course->name,
                'status' => $attendance->status
            ];
        }

        $gradeDao = new GradeDao;
        $grade = $gradeDao->getGradeById($gradeId);

        $data = [
            'date' => $date,
            'weekday_index' => $weekdayIndex,
            'grade_name' => $grade->name,
            'list' => $list
        ];

        return JsonBuilder::Success($data);
    }


    /**
     * 评分详情
     * @param AttendanceRequest $request
     * @return string
     */
    public function gradeDetails(AttendanceRequest $request) {
        $attendanceId = $request->getAttendanceId();
        $dao = new AttendancesDao();
        $attendance = $dao->getAttendanceById($attendanceId);
//        if($attendance->status == Attendance::STATUS_UN_EVALUATE) {
//            return JsonBuilder::Error('该课堂未评价');
//        }

        $gradeUserDao = new GradeUserDao();
        $return = $gradeUserDao->getGradeUserPageGradeId($attendance->grade_id);
        $students = $return->getCollection();

        $dao = new AttendancesDetailsDao();
        $details = $dao->getAttendDetailsByAttendanceId($attendanceId);
        $scores = array_column($details->toArray(),'score','student_id');


        $list = [];
        foreach ($students as $key => $item) {
            $list[$key]['username'] = $item->name;
            if(array_key_exists($item->user_id,$scores)) {
                $list[$key]['score'] = $scores[$item->user_id];
            } else {
                $list[$key]['score'] = 0;
            }
        }

        $data = [
            'currentPage' => $return->currentPage(),
            'lastPage'    => $return->lastPage(),
            'total'       => $return->total(),
            'teacher' => $attendance->teacher->name,
            'data'        => $list
        ];

        return JsonBuilder::Success($data);


    }







}