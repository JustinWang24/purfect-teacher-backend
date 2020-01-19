<?php


namespace App\Http\Controllers\Api\AttendanceSchedule;


use Carbon\Carbon;
use App\Utils\JsonBuilder;
use App\Dao\Schools\GradeDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Courses\CourseMajorDao;
use App\Http\Controllers\Controller;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\AttendanceSchedules\AttendancesDao;
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

        $user = $request->user();
        $grade = $user->gradeUser->grade;
        $school = $user->gradeUser->school;
        $configuration = $school->configuration;
        // 学年
        $year = $request->get('year',$configuration->getSchoolYear());
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
        $year = $request->get('year',$configuration->getSchoolYear());
        // 学期
        $term = $request->get('term',$configuration->guessTerm(Carbon::now()->month));

        $attendancesDetailsDao = new AttendancesDetailsDao();
        $signInList = $attendancesDetailsDao->signInList($year, $user->id, $courseId, $term);
        foreach ($signInList as $key => $val) {
            $signInList[$key]['time_slots'] = $val->timetable->timeSlot->name;
            $signInList[$key]['weekday_index'] = $val->timetable->weekday_index;
            $signInList[$key]['time'] = Carbon::parse($val->date)->format('H:i');
            $signInList[$key]['date'] = Carbon::parse($val->date)->format('Y-m-d');

            // 判断请假的
            if($val['mold'] == AttendancesDetail::MOLD_LEAVE) {
                if($val['status'] !== AttendancesDetail::STATUS_CONSENT) {
                    unset($signInList[$key]);
                }
            }
            unset($val->status);
            unset($val->timetable);
            unset($val->timetable_id);
        }
        $data = array_merge($signInList->toArray());
        return JsonBuilder::Success($data);
    }



    /**
     * 添加旷课记录
     * @param AttendanceRequest $request
     * @return string
     */
    public function addTruantRecord(AttendanceRequest $request) {

        $truant = $request->getTruantData();
        $timeTableDao = new TimetableItemDao();
        $item = $timeTableDao->getItemById($truant['timetable_id']);
        $data = Carbon::parse($truant['date']);
        $week = $item->school->configuration->getScheduleWeek($data)->getScheduleWeekIndex();

        $attendanceDao = new AttendancesDao();
        $attendanceInfo = $attendanceDao->getAttendanceByTimeTableId($item->year,$item->id, $item->term, $week);
        if(is_null($attendanceInfo)) {
            return JsonBuilder::Error('该课程还没上');
        }
        $truant['attendance_id'] = $attendanceInfo->id;
        $truant['course_id']     = $item->course_id;
        $truant['year']          = $item->year;
        $truant['term']          = $item->term;
        $truant['week']          = $week;
        $truant['mold']          = AttendancesDetail::MOLD_TRUANT;
        $truant['weekday_index'] = $item->weekday_index;
        $truant['date']          = $data;
        $re = $dao = new AttendancesDetailsDao();
        if(!empty($re)) {
            return JsonBuilder::Success('旷课已添加');
        }
        $dao->getTruantDetailByUserId($truant['student_id'],$data,$item->id);
        $result = $dao->add($truant);
        if($result) {
            return JsonBuilder::Success('旷课添加成功');
        } else {
            return JsonBuilder::Error('旷课添加失败');
        }
    }


    /**
     * 教师端 班级签到  -- 教师教的所有班级
     * @param AttendanceRequest $request
     * @return string
     */
    public function gradeSign(AttendanceRequest $request)
    {
        $teacher = $request->user();
        $dao = new TimetableItemDao;
        $item = $dao->getTeacherTeachingGrade($teacher->id);
        $gradeId = [];
        foreach ($item as $key => $value) {
            $gradeId[$key] = $value->grade_id;
        }
        $gradeId = array_unique($gradeId);

        $gradeDao = new GradeDao;
        $grades = $gradeDao->getGrades($gradeId);

        $data = [];
        foreach ($grades as $k => $v) {
            $data[$k]['grade_id'] = $v->id;
            $data[$k]['name'] = $v->name;
        }
        return JsonBuilder::Success($data);
    }


    /**
     * 教师端 班级签到列表
     * @param AttendanceRequest $request
     * @return string
     */
    public function courseSign(AttendanceRequest $request)
    {
        $gradeId = $request->get('grade_id');
        $teacherId = $request->user()->id;
        $date = $request->get('date', Carbon::now()->toDateString());
        $dao = new AttendancesDao();
        $return  = $dao->getAttendByDateTimeAndGradeIdAndTeacherId($date, $gradeId, $teacherId);
        $gradeDao = new GradeDao();
        $grade = $gradeDao->getGradeById($gradeId);


        $list = [];
        foreach ($return as $key => $item) {
            $list[$key]['attendance_id'] = $item->id;
            $list[$key]['course'] = $item->course->name;
            $list[$key]['time_slot'] = $item->timeTable->timeSlot->name;
            $list[$key]['total_number'] = $item->total_number;
            $list[$key]['actual_number'] = $item->actual_number;
            $list[$key]['leave_number'] = $item->leave_number;
            $list[$key]['missing_number'] = $item->missing_number;
            $list[$key]['status'] = $item->status;
        }
        $data = [
            'grade'=>['id'=>$gradeId,'name'=>$grade->name],
            'list' => $list
        ];

        return JsonBuilder::Success($data);
    }


    /**
     * 课程签到详情
     * @param AttendanceRequest $request
     * @return string
     */
    public function courseSignDetails(AttendanceRequest $request) {
        $attendanceId = $request->getAttendanceId();
        $gradeId = $request->get('grade_id');
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
                    $student[$key]['mold'] = AttendancesDetail::MOLD_TRUANT;
                    $score[$key]['score'] = 0;
                }
            }

        }
        // 旷课
        $truant = $total - $signin - $leave;

        $data = [
            'stat' => ['total'=>$total, 'signin'=>$signin, 'leave'=>$leave, 'truant'=>$truant],
            'signin' => $student,
            'score' => $score,
        ];
        return JsonBuilder::Success($data);
    }




}
